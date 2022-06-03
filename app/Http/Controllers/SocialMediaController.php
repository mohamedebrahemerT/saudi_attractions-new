<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSocialMediaRequest;
use App\Http\Requests\UpdateSocialMediaRequest;
use App\Media;
use App\Models\Locale;
use App\Repositories\SocialMediaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SocialMediaController extends AppBaseController
{
    /** @var  SocialMediaRepository */
    private $socialMediaRepository;

    public function __construct(SocialMediaRepository $socialMediaRepo)
    {
        $this->socialMediaRepository = $socialMediaRepo;
    }

    /**
     * Display a listing of the SocialMedia.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->socialMediaRepository->pushCriteria(new RequestCriteria($request));
        $socialMedia = $this->socialMediaRepository->all();
        $languages=Locale::where('default',1)->get();

        return view('social_media.index')
            ->with('socialMedia', $socialMedia)->with('languages', $languages);
    }

    /**
     * Show the form for creating a new SocialMedia.
     *
     * @return Response
     */
    public function create()
    {
        return view('social_media.create');
    }

    /**
     * Store a newly created SocialMedia in storage.
     *
     * @param CreateSocialMediaRequest $request
     *
     * @return Response
     */
    public function store(CreateSocialMediaRequest $request)
    {
        $input=[];
        $fileName=uploadFile($request->file('image'),'social_media');
        $media=Media::create(['image'=>$fileName]);
        $input['media_id']=$media->id;
        $translation_input=$request->only('name');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);

        $socialMedia = $this->socialMediaRepository->create($data);

        Flash::success('Social Media saved successfully.');

        return redirect(route('socialMedia.index'));
    }

    /**
     * Display the specified SocialMedia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $socialMedia = $this->socialMediaRepository->findWithoutFail($id);

        if (empty($socialMedia)) {
            Flash::error('Social Media not found');

            return redirect(route('socialMedia.index'));
        }

        return view('social_media.show')->with('socialMedia', $socialMedia);
    }

    /**
     * Show the form for editing the specified SocialMedia.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $socialMedia = $this->socialMediaRepository->findWithoutFail($id);

        if (empty($socialMedia)) {
            Flash::error('Social Media not found');

            return redirect(route('socialMedia.index'));
        }

        return view('social_media.edit')->with('socialMedia', $socialMedia);
    }

    /**
     * Update the specified SocialMedia in storage.
     *
     * @param  int              $id
     * @param UpdateSocialMediaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSocialMediaRequest $request)
    {
        $socialMedia = $this->socialMediaRepository->findWithoutFail($id);

        if (empty($socialMedia)) {
            Flash::error('Social Media not found');

            return redirect(route('socialMedia.index'));
        }
        $input=[];
        if($request->has('image')) {
            $fileName = uploadFile($request->file('image'), 'social_media');
            $media = Media::create(['image' => $fileName]);
            $input['media_id'] = $media->id;
        }
        $translation_input=$request->only('name');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $socialMedia = $this->socialMediaRepository->update($data, $id);

        Flash::success('Social Media updated successfully.');

        return redirect(route('socialMedia.index'));
    }

    /**
     * Remove the specified SocialMediaSeeds from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $socialMedia = $this->socialMediaRepository->findWithoutFail($id);

        if (empty($socialMedia)) {
            Flash::error('Social Media not found');

            return redirect(route('socialMedia.index'));
        }

        $this->socialMediaRepository->delete($id);

        Flash::success('Social Media deleted successfully.');

        return redirect(route('socialMedia.index'));
    }
}
