<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAboutUsRequest;
use App\Http\Requests\UpdateAboutUsRequest;
use App\Models\Locale;
use App\Repositories\AboutUsRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class AboutUsController extends AppBaseController
{
    /** @var  AboutUsRepository */
    private $aboutUsRepository;

    public function __construct(AboutUsRepository $aboutUsRepo)
    {
        $this->aboutUsRepository = $aboutUsRepo;
    }

    /**
     * Display a listing of the AboutUs.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->aboutUsRepository->pushCriteria(new RequestCriteria($request));
        $languages=Locale::where('default',1)->get();
        $about_uses = $this->aboutUsRepository->all();

        return view('about_uses.index')
            ->with('about_uses', $about_uses)->with('languages', $languages);
    }

    /**
     * Display the specified AboutUs.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $aboutUs = $this->aboutUsRepository->findWithoutFail($id);

        if (empty($aboutUs)) {
            Flash::error('About Us not found');

            return redirect(route('about_uses.index'));
        }

        return view('about_uses.show')->with('aboutUs', $aboutUs);
    }

    /**
     * Show the form for editing the specified AboutUs.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $aboutUs = $this->aboutUsRepository->findWithoutFail($id);

        if (empty($aboutUs)) {
            Flash::error('About Us not found');

            return redirect(route('about_uses.index'));
        }

        return view('about_uses.edit')->with('aboutUs', $aboutUs);
    }

    /**
     * Update the specified AboutUs in storage.
     *
     * @param  int              $id
     * @param UpdateAboutUsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateAboutUsRequest $request)
    {
        $aboutUs = $this->aboutUsRepository->findWithoutFail($id);

        if (empty($aboutUs)) {
            Flash::error('About Us not found');

            return redirect(route('about_uses.index'));
        }

        $input=[];
        $translation_input=$request->only('paragraph');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);

        $aboutUs = $this->aboutUsRepository->update($data, $id);

        Flash::success('About Us updated successfully.');

        return redirect(route('about_uses.index'));
    }

}
