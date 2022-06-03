<?php

namespace App\Http\Controllers;

use App\AttractionAddon;
use App\AttractionExceptionalDate;
use App\AttractionOpeningHour;
use App\AttractionTag;
use App\AttractionTicketDay;
use App\AttractionTicket;
use App\AttractionWeekDay;
use App\Http\Requests\CreateAttractionDaysRequest;
use App\Http\Requests\CreateAttractionRequest;
use App\Http\Requests\UpdateAttractionRequest;
use App\Http\Requests\DaysAttractionRequest;
use App\Media;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\Locale;
use App\Models\SocialMedia;
use App\Models\SubCategory;
use App\Permission;
use App\Repositories\AttractionRepository;
use App\Http\Controllers\AppBaseController;
use App\VenueDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AttractionController extends AppBaseController
{
    /** @var  AttractionRepository */
    private $attractionRepository;

    public function __construct(AttractionRepository $attractionRepo)
    {
        $this->attractionRepository = $attractionRepo;
    }

    /**
     * Display a listing of the Attraction.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->attractionRepository->pushCriteria(new RequestCriteria($request));
        $permission = Permission::where('name', 'attractions.publish')->orWhere('name', 'attractions.notification')->orWhere('name', 'attractions.editable')->orWhere('name', 'attractions.create')->orWhere('name', 'attractions.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['attractions.editable']))
        {
            $attractions = $this->attractionRepository->paginate(15);;
        }
        else{
            $attractions = Attraction::where('is_editable', 1)->paginate(15);;
        }
        $languages=Locale::where('default',1)->get();
        return view('attractions.index')
            ->with('attractions', $attractions)->with('languages', $languages)->with('permission', $permission);
    }

    /**
     * Show the form for creating a new Attraction.
     *
     * @return Response
     */
    public function create()
    {
        $categories= Category::all();
        $sub_categories= SubCategory::all();
        $social_media= SocialMedia::all();
        $days= VenueDay::all();
        return view('attractions.create')->with('categories', $categories)->with('sub_categories', $sub_categories)
            ->with('social_media', $social_media)->with('days', $days);
    }

    /**
     * Store a newly created Attraction in storage.
     *
     * @param CreateAttractionRequest $request
     *
     * @return Response
     */

    public function store(CreateAttractionRequest $request)
    {
        $input=$request->only('address_url', 'lat', 'lng', 'contact_numbers','is_featured', 'is_sponsored',
            'cash_on_delivery', 'national_id', 'credit_card', 'pay_later','max_of_pay_later_tickets', 'max_of_cash_tickets', 'week_suggest', 'number_of_days',
            'arabic_notification_title', 'arabic_notification_description', 'english_notification_title', 'english_notification_description', 'free', 'max_of_free_tickets');
        if($request->has('image')) {
            $fileName = uploadFile($request->file('image'), 'attractions');
            $media = Media::create(['image' => $fileName]);
            $input['media_id'] = $media->id;
        }

        $input['contact_numbers'] = implode(',', $request->input('contact_numbers'));
        $translation_input=$request->only('title', 'description', 'address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $attraction = $this->attractionRepository->create($data);

        $gallery=[];
        if($request->file('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'attractions');
                    $media = Media::create(['image' => $fileName]);
                    $gallery[] = $media->id;
                }
            }
        }

        $social_media_array=[];
        foreach ($request->input('social_media') as $key=>$value){
            $social_media_array[$key] = [
                'url' => $value['url'],
                'name' => $value['name']
            ];
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
        $attraction->tags()->createMany($tags);
        $attraction->social_media()->attach($social_media_array);
        $attraction->gallery()->attach($gallery);
        $attraction->categories()->attach($request->input('categories'));
        $attraction->sub_categories()->attach($request->input('sub_categories'));
        $attraction->attraction_tickets()->createMany($request->input('attraction_tickets'));
        $attraction->attraction_addons()->createMany($request->input('attraction_addons'));
        Flash::success('Attraction saved successfully.');

        return redirect(route('attractions.days',[$attraction->id]));
    }

    public function getDays($id)
    {

        $attraction = Attraction::find($id);
        $days= VenueDay::all();

        $types = AttractionTicket::listsTranslations('type')->where('attraction_id', $id)->get();
        $addons = AttractionAddon::listsTranslations('name')->where('attraction_id', $id)->get();

        return view('attractions.days')->with('days', $days)->with('types', $types)->with('addons', $addons)->with('attraction',$attraction);
    }

    public function storeDays(DaysAttractionRequest $request,$id)
    {
        $attraction = Attraction::find($id);

        $input=[];

        foreach ($request->input('attraction_week_days') as $key=> $week_day)
        {
            $input = $week_day;
            $input['is_closed']=0;
            if(isset($week_day['is_closed']) && $week_day['is_closed'] == 1 ){
                $input['is_closed'] =1;
            }


            $options = AttractionWeekDay::create(['attraction_id' => $id, 'venue_day_id' => $week_day['venue_day_id'],
                'start_time'=>$week_day['start_time'], 'end_time'=>$week_day['end_time'], 'is_closed'=> $input['is_closed']]);

                if(isset($week_day['types']) && !empty($week_day['types'])) {
                    $options->types()->attach($week_day['types']);
                }
                if(isset($week_day['addons']) && !empty($week_day['addons'])) {
                    $options->addons()->attach($week_day['addons']);
                }
        }


        foreach ($request->input('attraction_exceptional_dates') as $key=> $exceptional_date)
        {
               $exceptional =  AttractionExceptionalDate::create(['attraction_id' => $id,
                    'start_time' => $exceptional_date['start_time'], 'end_time' => $exceptional_date['end_time'],
                    'date' => $exceptional_date['date']]);

            if(isset($exceptional_date['types']) && !empty($exceptional_date['types'])) {
                $exceptional->types()->attach($exceptional_date['types']);
            }
            if(isset($exceptional_date['addons']) && !empty($exceptional_date['addons'])) {
                $exceptional->addons()->attach($exceptional_date['addons']);
            }
        }



        Flash::success('Attraction saved successfully.');

        return redirect(route('attractions.index'));

    }



    /**
     * Display the specified Attraction.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $attraction = $this->attractionRepository->findWithoutFail($id);

        if (empty($attraction)) {
            Flash::error('Attraction not found');

            return redirect(route('attractions.index'));
        }

        return view('attractions.show')->with('attraction', $attraction);
    }

    /**
     * Show the form for editing the specified Attraction.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $attraction = $this->attractionRepository->findWithoutFail($id);
        $permission = Permission::where('name', 'attractions.publish')->orWhere('name', 'attractions.notification')->orWhere('name', 'attractions.editable')->orWhere('name', 'attractions.create')->orWhere('name', 'attractions.destroy')->pluck('id', 'name')->toArray();
        if (empty($attraction)) {
            Flash::error('Attraction not found');

            return redirect(route('attractions.index'));
        }
        if(\Auth::user()->role->roles_permission->contains($permission['attractions.editable']) || $attraction->is_editable == 1) {
            $categories = Category::all();
            $sub_categories = SubCategory::all();
            $social_media = SocialMedia::all();
            return view('attractions.edit')->with('attraction', $attraction)->with('categories', $categories)
                ->with('sub_categories', $sub_categories)->with('social_media', $social_media);
        }
        return redirect(route('attractions.index'));
    }

    /**
     * Update the specified Attraction in storage.
     *
     * @param  int              $id
     * @param DaysAttractionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAttractionRequest $request)
    {
        $attraction = $this->attractionRepository->findWithoutFail($id);

        if (empty($attraction)) {
            Flash::error('Attraction not found');

            return redirect(route('attractions.index'));
        }

        $input=$request->only('address_url', 'national_id', 'lat', 'lng', 'contact_numbers', 'max_of_pay_later_tickets', 'max_of_cash_tickets', 'number_of_days',
        'arabic_notification_title', 'arabic_notification_description', 'english_notification_title', 'english_notification_description', 'free', 'max_of_free_tickets');
        $input['is_sponsored']=0;
        if($request->input('is_sponsored') == 1 ){
            $input['is_sponsored'] =1;
        }
        $input['is_featured']=0;
        if($request->input('is_featured') == 1 ){
            $input['is_featured'] =1;
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
        $input['national_id']=0;
        if($request->input('national_id') == 1 ){
            $input['national_id'] =1;
        }
        if($request->has('image')) {
            $fileName = uploadFile($request->file('image'), 'attractions');
            $media = Media::create(['image' => $fileName]);
            $input['media_id'] = $media->id;
        }
        $input['contact_numbers'] = implode(',', $request->input('contact_numbers'));
        $translation_input=$request->only('title', 'description', 'address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);

        $attraction = $this->attractionRepository->update($data, $id);

        $gallery=[];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'attractions');
                    $media = Media::create(['image' => $fileName]);
                    $gallery[] = $media->id;
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


        $ids=array_column($request->input('attraction_tickets'),'id');
        AttractionTicket::whereNotIn('id',$ids)->where('attraction_id',$id)->delete();
        foreach ($request->input('attraction_tickets') as $key=> $ticket_type)
        {
            if(isset($ticket_type['id'])){
                AttractionTicket::find($ticket_type['id'])->update(['type'=>$ticket_type['type'],  'description'=>$ticket_type['description'],
                    'price'=>$ticket_type['price'], 'number_of_tickets'=>$ticket_type['number_of_tickets']]);
            }
            else{
                AttractionTicket::create(['attraction_id'=>$id, 'type'=>$ticket_type['type'],  'description'=>$ticket_type['description'],
                    'price'=>$ticket_type['price'], 'number_of_tickets'=>$ticket_type['number_of_tickets']]);
            }
        }

        $ids=array_column($request->input('attraction_addons'),'id');
        AttractionAddon::whereNotIn('id',$ids)->where('attraction_id',$id)->delete();
        foreach ($request->input('attraction_addons') as $key=> $ticket_addon)
        {
            if(isset($ticket_addon['id'])){
                AttractionAddon::find($ticket_addon['id'])->update(['name'=>$ticket_addon['name'],  'description'=>$ticket_addon['description'],
                    'price'=>$ticket_addon['price'], 'number_of_tickets'=>$ticket_addon['number_of_tickets']]);
            }
            else{
                AttractionAddon::create(['attraction_id'=>$id, 'name'=>$ticket_addon['name'],  'description'=>$ticket_addon['description'],
                    'price'=>$ticket_addon['price'], 'number_of_tickets'=>$ticket_addon['number_of_tickets']]);
            }
        }


        if(!empty($request->input('tags'))){
            // return "dasda";
        
        $ids=array_column($request->input('tags'),'id');
        AttractionTag::whereNotIn('id',$ids)->where('attraction_id',$id)->delete();
        $tags=$request->file('tags');

        foreach ($request->input('tags') as $key=> $tag) {
            if (isset($tag['id'])) {
                if(isset($tags[$key]['image'])) {
                    $file = $tags[$key]['image'];
                    if ($file->isValid()) {
                        $fileName = uploadFile($file, 'tags');
                        $tag_media = Media::create(['image' => $fileName]);
                        $media_id = $tag_media->id;
                        $input['media_id'] = $media_id;
                    }
                }
                $input['name']=$tag['name'];
                AttractionTag::find($tag['id'])->update($input);
            } else {
                if(isset($tags[$key]['image'])) {
                    $file = $tags[$key]['image'];
                    if ($file->isValid()) {
                        $fileName = uploadFile($file, 'tags');
                        $tag_media = Media::create(['image' => $fileName]);
                        $media_id = $tag_media->id;
                        $input['media_id'] = $media_id;
                    }
                }
                $input['name']=$tag['name'];
                $input['attraction_id']=$id;
                AttractionTag::create($input);
            }
        }
    }
        $attraction->social_media()->sync($social_media_array);
        $gallery = array_merge($gallery,(array)$request->input('media'));
        $attraction->gallery()->sync($gallery);
        $attraction->categories()->sync($request->input('categories'));
        $attraction->sub_categories()->sync($request->input('sub_categories'));
        Flash::success('Attraction updated successfully.');

        return redirect(route('attractions.edit_days',[$attraction->id]).'?lang='.App::getLocale());
    }

    public function editDays($id)
    {
        $attraction = Attraction::find($id);
        $days= VenueDay::all();

        $types = AttractionTicket::listsTranslations('type')->where('attraction_id', $id)->get();
        $addons = AttractionAddon::listsTranslations('name')->where('attraction_id', $id)->get();

        return view('attractions.edit_days')->with('days', $days)->with('attraction', $attraction)->with('types', $types)->with('addons', $addons);
    }

    public function updateDays(DaysAttractionRequest $request, $id)
    {
        $attraction = Attraction::find($id);

        $input=[];

        foreach ($request->input('attraction_week_days') as $key=> $week_day)
        {
            $input = $week_day;
            $week_day['is_closed']=0;
            if(isset($week_day['is_closed']) && $week_day['is_closed'] == 1 ){
                $week_day['is_closed'] =1;
            }

            if(isset($week_day['day_id'])){
               $options =  AttractionWeekDay::find($week_day['day_id']);

               $options->update(['start_time'=>$week_day['start_time'],
                    'end_time'=>$week_day['end_time'] , 'is_closed'=> $week_day['is_closed']]);

                if(isset($week_day['types']) && !empty($week_day['types'])) {
                    $options->types()->sync($week_day['types']);
                }
                if(isset($week_day['addons']) && !empty($week_day['addons'])) {
                    $options->addons()->sync($week_day['addons']);
                }
            }

            else{

                $options = AttractionWeekDay::create(['attraction_id' => $id, 'venue_day_id' => $week_day['venue_day_id'],
                    'start_time'=>$week_day['start_time'], 'end_time'=>$week_day['end_time'], 'is_closed'=> $input['is_closed']]);

                if(isset($week_day['types']) && !empty($week_day['addons'])) {
                    $options->types()->attach($week_day['types']);
                }
                    if(isset($week_day['addons']) && !empty($week_day['addons'])) {
                        $options->addons()->attach($week_day['addons']);
                }
            }
        }

        foreach ($request->input('attraction_exceptional_dates') as $key=> $exceptional_date)
        {

            if(isset($exceptional_date['id'])){
               $exceptional = AttractionExceptionalDate::find($exceptional_date['id']);

               $exceptional->update(['start_time' => $exceptional_date['start_time'], 'end_time' => $exceptional_date['end_time'],
                    'date' => $exceptional_date['date']]);


                if(isset($exceptional_date['types']) && !empty($exceptional_date['types'])) {
                    $exceptional->types()->sync($exceptional_date['types']);
                }
                if(isset($exceptional_date['addons']) && !empty($exceptional_date['addons'])) {
                    $exceptional->addons()->sync($exceptional_date['addons']);
                }
            }
            else{
                $exceptional = AttractionExceptionalDate::create(['attraction_id' => $id, 'start_time' => $exceptional_date['start_time'], 'end_time' => $exceptional_date['end_time'],
                    'date' => $exceptional_date['date']]);

                if(isset($exceptional_date['types']) && !empty($exceptional_date['types'])) {
                    $exceptional->types()->attach($exceptional_date['types']);
                }
                if(isset($exceptional_date['addons']) && !empty($exceptional_date['addons'])) {
                    $exceptional->addons()->attach($exceptional_date['addons']);

                }
            }
        }



        Flash::success('Attraction saved successfully.');

        return redirect(route('attractions.index'));
    }

    /**
     * Remove the specified Attraction from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $attraction = $this->attractionRepository->findWithoutFail($id);

        if (empty($attraction)) {
            Flash::error('Attraction not found');

            return redirect(route('attractions.index'));
        }

        $this->attractionRepository->delete($id);

        Flash::success('Attraction deleted successfully.');

        return redirect(route('attractions.index'));
    }

    public function pushNotification($id)
    {
        $attraction = Attraction::find($id);
        $notification = pushNotification([], ['en'=>$attraction->english_notification_title, 'ar'=>$attraction->arabic_notification_title],['en'=>$attraction->english_notification_description, 'ar'=>$attraction->arabic_notification_description],['type'=>'attractions','id'=>$attraction->id]);

        Flash::success('Push Notification sent successfully.');

        return redirect(route('attractions.index'));
    }

    public function publish($id)
    {
        $attraction = Attraction::find($id);
        $attraction->update(['draft'=>1]);

        Flash::success('Attraction has been published successfully.');

        return redirect(route('attractions.index'));
    }

    public function editable($id)
    {
        $attraction = Attraction::find($id);
        $attraction->update(['is_editable'=>1]);

        Flash::success('Attraction has sent to the user successfully.');

        return redirect(route('attractions.index'));
    }

    public function search(Request $request)
    {
        $this->attractionRepository->pushCriteria(new RequestCriteria($request));
        $permission = Permission::where('name', 'attractions.publish')->orWhere('name', 'attractions.notification')->orWhere('name', 'attractions.editable')->orWhere('name', 'attractions.create')->orWhere('name', 'attractions.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['attractions.editable']))
        {
            $attractions = Attraction::latest();
        }
        else{
            $attractions = Attraction::where('is_editable', 1);
        }
        
        $search = $_GET['search'];

        if (!empty($search)) {
            $attractions=$attractions->whereTranslationLike('title',  '%' . $search . '%')
            ->orWhereTranslationLike('description', '%' . $search . '%');
        }
     
        $attractions = $attractions->paginate(15);
        if(!$attractions){
            Flash::error('Attraction not found');
            return redirect(route('attractions.index'));
        }
        $languages=Locale::where('default',1)->get();
        return view('attractions.index')->with('attractions', $attractions)->with('languages', $languages)->with('permission', $permission); 
    }
}
