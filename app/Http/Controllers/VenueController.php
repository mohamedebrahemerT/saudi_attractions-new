<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVenueRequest;
use App\Http\Requests\UpdateVenueRequest;
use App\Media;
use App\Models\Category;
use App\Models\Locale;
use App\Models\SocialMedia;
use App\Models\SubCategory;
use App\Models\Venue;
use App\Permission;
use App\Repositories\VenueRepository;
use App\Http\Controllers\AppBaseController;
use App\VenueDay;
use App\VenueOpeningHour;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class VenueController extends AppBaseController
{
    /** @var  VenueRepository */
    private $venueRepository;

    public function __construct(VenueRepository $venueRepo)
    {
        $this->venueRepository = $venueRepo;
    }

    /**
     * Display a listing of the Venue.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->venueRepository->pushCriteria(new RequestCriteria($request));
        $permission = Permission::where('name', 'venues.publish')->orWhere('name', 'venues.notification')->orWhere('name', 'venues.editable')->orWhere('name', 'venues.create')->orWhere('name', 'venues.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['venues.editable']))
        {
            $venues = $this->venueRepository->paginate(15);
        }
      else{
          $venues = Venue::where('is_editable', 1)->paginate(15);
      }
        $languages=Locale::where('default',1)->get();

        return view('venues.index')
            ->with('venues', $venues)->with('languages', $languages)->with('permission', $permission);
    }

    /**
     * Show the form for creating a new Venue.
     *
     * @return Response
     */
    public function create()
    {
        $categories= Category::all();
        $sub_categories= SubCategory::all();
        $social_media= SocialMedia::all();
        $days= VenueDay::all();
        return view('venues.create')->with('categories', $categories)->with('sub_categories', $sub_categories)
            ->with('social_media', $social_media)->with('days', $days);
    }

    /**
     * Store a newly created Venue in storage.
     *
     * @param CreateVenueRequest $request
     *
     * @return Response
     */
    public function store(CreateVenueRequest $request)
    {
        $input=$request->only('location', 'lat', 'lng', 'is_featured', 'is_brand', 'is_sponsored', 'email', 'website', 'telephone_numbers', 'week_suggest', 
        'arabic_notification_title', 'arabic_notification_description', 'english_notification_title', 'english_notification_description');
        if($request->has('image')) {
            $fileName=uploadFile($request->file('image'),'venues');
            $media=Media::create(['image'=>$fileName]);
            $input['media_id']=$media->id;
        }

        $input['telephone_numbers'] = implode(',', $request->input('telephone_numbers'));
        $translation_input=$request->only('title', 'description', 'address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);

        $venue = $this->venueRepository->create($data);

        $gallery=[];
        if($request->file('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'venues');
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

        $venue->social_media()->attach($social_media_array);
        $venue->gallery()->attach($gallery);
        $venue->categories()->attach($request->input('categories'));
        $venue->sub_categories()->attach($request->input('sub_categories'));
        $venue->venue_opening_hours()->createMany($request->input('venue_opening_hours'));
        Flash::success('Venue saved successfully.');

        return redirect(route('venues.index'));
    }

    /**
     * Display the specified Venue.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $venue = $this->venueRepository->findWithoutFail($id);

        if (empty($venue)) {
            Flash::error('Venue not found');

            return redirect(route('venues.index'));
        }

        return view('venues.show')->with('venue', $venue);
    }

    /**
     * Show the form for editing the specified Venue.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $venue = $this->venueRepository->findWithoutFail($id);

        if (empty($venue)) {
            Flash::error('Venue not found');

            return redirect(route('venues.index'));
        }
        $permission = Permission::where('name', 'venues.publish')->orWhere('name', 'venues.notification')->orWhere('name', 'venues.editable')->orWhere('name', 'venues.create')->orWhere('name', 'venues.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['venues.editable']) || $venue->is_editable == 1){
            $categories= Category::all();
            $sub_categories = SubCategory::all();
            $social_media = SocialMedia::all();
            $days = VenueDay::all();
            return view('venues.edit')->with('venue', $venue)->with('categories', $categories)->with('sub_categories', $sub_categories)
                ->with('social_media', $social_media)->with('days', $days);
        }
        return redirect(route('venues.index'));
    }

    /**
     * Update the specified Venue in storage.
     *
     * @param  int              $id
     * @param UpdateVenueRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVenueRequest $request)
    {
        $venue = $this->venueRepository->findWithoutFail($id);

        if (empty($venue)) {
            Flash::error('Venue not found');

            return redirect(route('venues.index'));
        }

        $input=$request->only('location', 'lat', 'lng', 'email', 'website', 'telephone_numbers', 
        'arabic_notification_title', 'arabic_notification_description', 'english_notification_title', 'english_notification_description');
        $input['is_sponsored']=0;
        if($request->input('is_sponsored') == 1 ){
            $input['is_sponsored'] =1;
        }
        $input['is_featured']=0;
        if($request->input('is_featured') == 1 ){
            $input['is_featured'] =1;
        }
        $input['is_brand']=0;
        if($request->input('is_brand') == 1 ){
            $input['is_brand'] =1;
        }
        $input['week_suggest']=0;
        if($request->input('week_suggest') == 1 ){
            $input['week_suggest'] =1;
        }
        if($request->has('image')) {
            $fileName = uploadFile($request->file('image'), 'venues');
            $media = Media::create(['image' => $fileName]);
            $input['media_id'] = $media->id;
        }

        $input['telephone_numbers'] = implode(',', $request->input('telephone_numbers'));
        $translation_input=$request->only('title', 'description', 'address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $venue = $this->venueRepository->update($data, $id);
        $gallery=[];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'venues');
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

        $opening_hours = $request->input('venue_opening_hours');
        if($opening_hours) {
            foreach ($opening_hours as $key => $hour) {
                $input = $hour;
                $input['is_closed']=0;
                if(isset($hour['is_closed']) && $hour['is_closed'] == 1 ){
                    $input['is_closed'] =1;
                }
                VenueOpeningHour::find($hour['venue_day_id'])->update(['start_time'=>$input['start_time'], 'end_time'=>$input['end_time'], 'is_closed'=> $input['is_closed']]);
            }
        }

            $venue->social_media()->sync($social_media_array);
            $gallery = array_merge($gallery,(array)$request->input('media'));
            $venue->gallery()->sync($gallery);
            $venue->categories()->sync($request->input('categories'));
            $venue->sub_categories()->sync($request->input('sub_categories'));

        Flash::success('Venue updated successfully.');

        return redirect(route('venues.index'));
    }

    /**
     * Remove the specified Venue from storage.
     *
     * @param  int $id
     *
     * @return Response
     */

    public function destroy($id)
    {
        $venue = $this->venueRepository->findWithoutFail($id);

        if (empty($venue)) {
            Flash::error('Venue not found');

            return redirect(route('venues.index'));
        }

        $this->venueRepository->delete($id);

        Flash::success('Venue deleted successfully.');

        return redirect(route('venues.index'));
    }

    public function pushNotification($id)
    {
        $venue = Venue::find($id);
        $notification = pushNotification([],['en'=>$venue->english_notification_title, 'ar'=>$venue->arabic_notification_title],['en'=>$venue->english_notification_description, 'ar'=>$venue->arabic_notification_description] ,['type'=>'venues','id'=>$venue->id]);

        Flash::success('Push Notification sent successfully.');

        return redirect(route('venues.index'));
    }

    public function publish($id)
    {
        $venue = Venue::find($id);
        $venue->update(['draft'=>1]);

        Flash::success('Venue has been published successfully.');

        return redirect(route('venues.index'));
    }

    public function editable($id)
    {
        $venue = Venue::find($id);
        $venue->update(['is_editable'=>1]);

        Flash::success('Venue has sent to the user successfully.');

        return redirect(route('venues.index'));
    }

    public function search(Request $request)
    {
        $this->venueRepository->pushCriteria(new RequestCriteria($request));
        $permission = Permission::where('name', 'venues.publish')->orWhere('name', 'venues.notification')->orWhere('name', 'venues.editable')->orWhere('name', 'venues.create')->orWhere('name', 'venues.destroy')->pluck('id', 'name')->toArray();
        if(\Auth::user()->role->roles_permission->contains($permission['venues.editable']))
        {
            $venues = Venue::latest();
        }
        else{
            $venues = Venue::where('is_editable', 1);
        }
        
        $search = $_GET['search'];

        if (!empty($search)) {
            $venues=$venues->whereTranslationLike('title',  '%' . $search . '%')
            ->orWhereTranslationLike('description', '%' . $search . '%');
        }
     
        $venues = $venues->paginate(15);
        if(!$venues){
            Flash::error('Venue not found');
            return redirect(route('venues.index'));
        }
        $languages=Locale::where('default',1)->get();
        return view('venues.index')->with('venues', $venues)->with('languages', $languages)->with('permission', $permission); 
    }
}
