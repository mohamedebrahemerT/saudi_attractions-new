<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateContactUsRequest;
use App\Http\Requests\UpdateContactUsRequest;
use App\Repositories\ContactUsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use App\Models\Locale;
use App\ContactMedia;
use Flash;
use App\Media;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\ContactUs;
use Illuminate\Support\Facades\App;
use App\Permission;

class ContactUsController extends AppBaseController
{
    /** @var  ContactUsRepository */
    private $contactUsRepository;

    public function __construct(ContactUsRepository $contactUsRepo)
    {
        $this->contactUsRepository = $contactUsRepo;
    }

    /**
     * Display a listing of the ContactUs.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->contactUsRepository->pushCriteria(new RequestCriteria($request));
        $contactuses = $this->contactUsRepository->all();
        $languages=Locale::where('default',1)->get();


        return view('contactuses.index')
            ->with('contactuses', $contactuses)->with('languages', $languages);
    }

    /**
     * Show the form for creating a new ContactUs.
     *
     * @return Response
     */
    public function create()
    {
        return view('contactuses.create');
    }

    /**
     * Store a newly created ContactUs in storage.
     *
     * @param CreateContactUsRequest $request
     *
     * @return Response
     */
    public function store(CreateContactUsRequest $request)
    {
        $input = $request->only('telephone', 'email', 'website');
        $translation_input=$request->only('address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $contactUs = $this->contactUsRepository->create($data);

        $icons=[];
        if($request->file('contactMedia')) {
            foreach ($request->file('contactMedia') as $file) {
                $file=$file['image'];
                if ($file->isValid()) {
                    $fileName = uploadFile($file, 'contactMedia');
                    $media = Media::create(['image' => $fileName]);
                    $icons[] = $media->id;
                }
            }
        }
        $contactMedia=[];
        $media_names=$request->input('contactMedia');
        $media_names=array_values($media_names);
        foreach ($icons as $key=> $icon)
        {
            $mediaCon['media_id']=$icon;
            $mediaCon['name']=$media_names[$key]['name'];
            $mediaCon['url']=$media_names[$key]['url'];
            $contactMedia[]=$mediaCon;
        }
       
        $contactUs->contactMedia()->createMany($contactMedia);

        Flash::success('Contact Us saved successfully.');

        return redirect(route('contactuses.index'));
    }

    /**
     * Display the specified ContactUs.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $contactUs = $this->contactUsRepository->findWithoutFail($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        return view('contactuses.show')->with('contactUs', $contactUs);
    }

    /**
     * Show the form for editing the specified ContactUs.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $contactUs = $this->contactUsRepository->findWithoutFail($id);
        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        return view('contactuses.edit')->with('contactUs', $contactUs);
    }

    /**
     * Update the specified ContactUs in storage.
     *
     * @param  int              $id
     * @param UpdateContactUsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContactUsRequest $request)
    {
        $contactUs = $this->contactUsRepository->findWithoutFail($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }
        $input = $request->only('telephone', 'email', 'website');
        $translation_input=$request->only('address');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $contactUs = $this->contactUsRepository->update($data, $id);

     
        $ids= is_array($request->input('contactMedia'))? array_column($request->input('contactMedia'),'id'): array();
        
        ContactMedia::whereNotIn('id',$ids)->where('contactus_id',$id)->delete();
        $contactMedia=$request->file('contactMedia');
        $contactMediainput=$request->input('contactMedia');

        if($contactMedia || $contactMediainput) {
            foreach ($request->input('contactMedia') as $key => $mediaCon) {
                if (isset($mediaCon['id'])) {
                    if (isset($contactMedia[$key]['image'])) {
                        $file = $contactMedia[$key]['image'];
                        if ($file->isValid()) {
                            $fileName = uploadFile($file, 'contactMedia');
                            $contact_media = Media::create(['image' => $fileName]);
                            $media_id = $contact_media->id;
                            $input['media_id'] = $media_id;
                        }
                    }
                    $input['name'] = $mediaCon['name'];
                    $input['url'] = $mediaCon['url'];
                    ContactMedia::find($mediaCon['id'])->update($input);
                } else {
                    if (isset($contactMedia[$key]['image'])) {
                        $file = $contactMedia[$key]['image'];
                        if ($file->isValid()) {
                            $fileName = uploadFile($file, 'contactMedia');
                            $contact_media = Media::create(['image' => $fileName]);
                            $media_id = $contact_media->id;
                            $input['media_id'] = $media_id;
                        }
                    }
                    $input['name'] = $mediaCon['name'];
                    $input['url'] = $mediaCon['url'];
                    $input['contactus_id'] = $id;
                    ContactMedia::create($input);
                }
            }
        }


        Flash::success('Contact Us updated successfully.');

        return redirect(route('contactuses.index'));
    }

    /**
     * Remove the specified ContactUs from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $contactUs = $this->contactUsRepository->findWithoutFail($id);

        if (empty($contactUs)) {
            Flash::error('Contact Us not found');

            return redirect(route('contactuses.index'));
        }

        $this->contactUsRepository->delete($id);

        Flash::success('Contact Us deleted successfully.');

        return redirect(route('contactuses.index'));
    }
}
