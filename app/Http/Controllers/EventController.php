<?php

namespace App\Http\Controllers;

use App\EventDay;
use App\EventLocale;
use App\EventTicket;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Media;
use App\Models\Category;
use App\Models\Event;
use App\Models\Locale;
use App\Models\SocialMedia;
use App\Models\SubCategory;
use App\Permission;
use App\Repositories\EventRepository;
use App\Http\Controllers\AppBaseController;
use App\Tag;
use App\TicketDate;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class EventController extends AppBaseController
{
    /** @var  EventRepository */
    private $eventRepository;

    public function __construct(EventRepository $eventRepo)
    {
        $this->eventRepository = $eventRepo;
    }

    /**
     * Display a listing of the Event.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {        
        $this->eventRepository->pushCriteria(new RequestCriteria($request));
        $permission = Permission::where('name', 'events.publish')->orWhere('name', 'events.notification')->orWhere('name', 'events.editable')->orWhere('name', 'events.create')->orWhere('name', 'events.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['events.editable']))
        {
            $events = Event::latest()->paginate(15);
        }
        else{
            $events = Event::where('is_editable', 1)->paginate(15);
        }


        $languages=Locale::where('default',1)->get();
        return view('events.index')
            ->with('events', $events)->with('languages', $languages)->with('permission', $permission);
    }

    /**
     * Show the form for creating a new Event.
     *
     * @return Response
     */
    public function create()
    {
        $categories= Category::all();
        $sub_categories= SubCategory::all();
        $social_media= SocialMedia::all();
        return view('events.create')->with('categories', $categories)->with('sub_categories', $sub_categories)
            ->with('social_media', $social_media);
    }

    /**
     * Store a newly created Event in storage.
     *
     * @param CreateEventRequest $request
     *
     * @return Response
     */
    public function store(CreateEventRequest $request)
    {
        $input=$request->only('start_date', 'end_date', 'address_url', 'start_price', 'end_price', 'lat', 'lng', 'is_featured',
            'cash_on_delivery', 'credit_card', 'pay_later','max_of_pay_later_tickets', 'max_of_cash_tickets', 'national_id', 'week_suggest', 
            'arabic_notification_title', 'arabic_notification_description', 'english_notification_title', 'english_notification_description', 'free', 'max_of_free_tickets');


        if($request->has('image')) {
            $fileName = uploadFile($request->file('image'), 'events');
            $media = Media::create(['image' => $fileName]);
            $input['media_id'] = $media->id;
        }
        $translation_input=$request->only('title', 'description', 'address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $event = $this->eventRepository->create($data);

        $gallery=[];
        if($request->file('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'events');
                    $media = Media::create(['image' => $fileName]);
                    $gallery[] = $media->id;
                }
            }
        }

        $icons=[];
        if($request->file('tags')) {
            foreach ($request->file('tags') as $file) {
                $file=$file['image'];
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'tags');
                    $media = Media::create(['image' => $fileName]);
                    $icons[] = $media->id;
                }
            }
        }
        $tags=[];
        $tag_names=$request->input('tags');
        $tag_names=array_values($tag_names);
        foreach ($icons as $key=> $icon)
        {
            $tag['media_id']=$icon;
            $tag['name']=$tag_names[$key]['name'];
            $tags[]=$tag;
        }
        $event->gallery()->attach($gallery);
        $event->categories()->attach($request->input('categories'));
        $event->sub_categories()->attach($request->input('sub_categories'));
        $event->event_days()->createMany($request->input('event_days'));
        $event->tags()->createMany($tags);
        $event->event_tickets()->createMany($request->input('event_tickets'));
        $event->ticket_dates()->createMany($request->input('ticket_dates'));


        $social_media_array=[];
        foreach ($request->input('social_media') as $key=>$value){
            $social_media_array[$key] = [
                'url' => $value['url'],
                'name' => $value['name']
            ];
        }
        $event->social_media()->attach($social_media_array);

        Flash::success('Event saved successfully.');

        return redirect(route('events.index'));
    }

    /**
     * Display the specified Event.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $event = $this->eventRepository->findWithoutFail($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }

        return view('events.show')->with('event', $event);
    }

    /**
     * Show the form for editing the specified Event.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $event = $this->eventRepository->findWithoutFail($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }
        $permission = Permission::where('name', 'events.publish')->orWhere('name', 'events.notification')->orWhere('name', 'events.editable')->orWhere('name', 'events.create')->orWhere('name', 'events.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['events.editable']) || $event->is_editable == 1) {
            $categories = Category::all();
            $sub_categories = SubCategory::all();
            $social_media = SocialMedia::all();

            return view('events.edit')->with('event', $event)->with('categories', $categories)->with('sub_categories', $sub_categories)
                ->with('social_media', $social_media);
        }
        return redirect(route('events.index'));
    }

    /**
     * Update the specified Event in storage.
     *
     * @param  int              $id
     * @param UpdateEventRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEventRequest $request)
    {
        $event = $this->eventRepository->findWithoutFail($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }

        $input=$request->only('start_date', 'end_date', 'address_url', 'start_price', 'end_price', 'lat', 'lng', 'is_featured',
        'cash_on_delivery', 'credit_card', 'pay_later','max_of_pay_later_tickets', 'max_of_cash_tickets', 'national_id', 'week_suggest',
        'arabic_notification_title', 'arabic_notification_description', 'english_notification_title', 'english_notification_description', 'free', 'max_of_free_tickets');
        $input['is_featured']=0;
        if($request->input('is_featured') == 1 ){
            $input['is_featured'] =1;
        }
        $input['national_id']=0;
        if($request->input('national_id') == 1 ){
            $input['national_id'] =1;
        }
        $input['week_suggest']=0;
        if($request->input('week_suggest') == 1 ){
            $input['week_suggest'] =1;
        }
        $input['cash_on_delivery']=0;
        if($request->input('cash_on_delivery') == 1 ){
            $input['cash_on_delivery'] =1;
        }
        $input['credit_card']=0;
        if($request->input('credit_card') == 1 ){
            $input['credit_card'] =1;
        }
        $input['pay_later']=0;
        if($request->input('pay_later') == 1 ){
            $input['pay_later'] =1;
        }
        $input['free']=0;
        if($request->input('free') == 1 ){
            $input['free'] =1;
        }
        if($request->has('image')) {
            $fileName = uploadFile($request->file('image'), 'events');
            $media = Media::create(['image' => $fileName]);
            $input['media_id'] = $media->id;
        }
        $translation_input=$request->only('title', 'description', 'address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);

        $event = $this->eventRepository->update($data, $id);

        $gallery=[];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'events');
                    $media = Media::create(['image' => $fileName]);
                    $gallery[] = $media->id;
                }
            }

            $gallery=array_merge($gallery,(array)$request->input('media'));
            $event->gallery()->sync($gallery);
        }

        $ids= is_array($request->input('tags'))? array_column($request->input('tags'),'id'): array();
        Tag::whereNotIn('id',$ids)->where('event_id',$id)->delete();
        $tags=$request->file('tags');
if($tags) {
    foreach ($request->input('tags') as $key => $tag) {
        if (isset($tag['id'])) {
            if (isset($tags[$key]['image'])) {
                $file = $tags[$key]['image'];
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'tags');
                    $tag_media = Media::create(['image' => $fileName]);
                    $media_id = $tag_media->id;
                    $input['media_id'] = $media_id;
                }
            }
            $input['name'] = $tag['name'];
            Tag::find($tag['id'])->update($input);
        } else {
            if (isset($tags[$key]['image'])) {
                $file = $tags[$key]['image'];
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'tags');
                    $tag_media = Media::create(['image' => $fileName]);
                    $media_id = $tag_media->id;
                    $input['media_id'] = $media_id;
                }
            }
            $input['name'] = $tag['name'];
            $input['event_id'] = $id;
            Tag::create($input);
        }
    }
}



        $social_media_array=[];
        foreach ($request->input('social_media_inputs') as $key=>$value){
            $social_media_array[$key] = [
                'url' => $value['url'],
                'name' => $value['name']
            ];
        }
        $event->social_media()->sync($social_media_array);

        $ids= is_array($request->input('event_tickets'))? array_column($request->input('event_tickets'),'id'): array();
        EventTicket::whereNotIn('id',$ids)->where('event_id',$id)->delete();
        foreach ($request->input('event_tickets') as $key=> $ticket)
        {
            if(isset($ticket['id'])){
                EventTicket::find($ticket['id'])->update(['type'=>$ticket['type'],  'description'=>$ticket['description'],
                    'price'=>$ticket['price'], 'number_of_tickets'=>$ticket['number_of_tickets']]);
            }
            else{
                EventTicket::create(['event_id'=>$id, 'type'=>$ticket['type'],  'description'=>$ticket['description'],
                    'price'=>$ticket['price'], 'number_of_tickets'=>$ticket['number_of_tickets']]);
            }
        }

        $ids= is_array($request->input('ticket_dates'))? array_column($request->input('ticket_dates'),'id'): array();
        TicketDate::whereNotIn('id',$ids)->where('event_id',$id)->delete();
        foreach ($request->input('ticket_dates') as $key=> $ticket_date)
        {
            if(isset($ticket_date['id'])){
                TicketDate::find($ticket_date['id'])->update(['date'=>$ticket_date['date'],  'time'=>$ticket_date['time']]);
            }
            else{
                TicketDate::create(['event_id'=>$id, 'date'=>$ticket_date['date'], 'time'=>$ticket_date['time']]);
            }
        }

        $event->categories()->sync($request->input('categories'));
        $event->sub_categories()->sync($request->input('sub_categories'));

        Flash::success('Event updated successfully.');

        return redirect(route('events.index'));
    }

    /**
     * Remove the specified Event from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $event = $this->eventRepository->findWithoutFail($id);

        if (empty($event)) {
            Flash::error('Event not found');

            return redirect(route('events.index'));
        }

        $this->eventRepository->delete($id);

        Flash::success('Event deleted successfully.');

        return redirect(route('events.index'));
    }

    public function pushNotification($id)
    {
        $event = Event::find($id);

        $notification = pushNotification([],['en'=>$event->english_notification_title, 'ar'=>$event->arabic_notification_title],['en'=>$event->english_notification_description, 'ar'=>$event->arabic_notification_description],['type'=>'events','id'=>$event->id]);

        Flash::success('Push Notification sent successfully.');

        return redirect(route('events.index'));
    }

    public function publish($id)
    {
        $event = Event::find($id);
        $event->update(['draft'=>1]);

        Flash::success('Event has been published successfully.');

        return redirect(route('events.index'));
    }

    public function editable($id)
    {
        $event = Event::find($id);
        $event->update(['is_editable'=>1]);

        Flash::success('Event has sent to the user successfully.');

        return redirect(route('events.index'));
    }


    
    public function search(Request $request)
    {

        $this->eventRepository->pushCriteria(new RequestCriteria($request));
        $permission = Permission::where('name', 'events.publish')->orWhere('name', 'events.notification')->orWhere('name', 'events.editable')->orWhere('name', 'events.create')->orWhere('name', 'events.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['events.editable']))
        {
            $events = Event::latest();
        }
        else{
            $events = Event::where('is_editable', 1);
        }
        
        $search = $_GET['search'];

        if (!empty($search)) {
            $events=$events->whereTranslationLike('title',  '%' . $search . '%')
            ->orWhereTranslationLike('description', '%' . $search . '%');
        }
     
        $events = $events->paginate(15);
        if(!$events){
            Flash::error('Event not found');
            return redirect(route('events.index'));
        }
        $languages=Locale::where('default',1)->get();
        return view('events.index')->with('events', $events)->with('languages', $languages)->with('permission', $permission);

        
    }

}
