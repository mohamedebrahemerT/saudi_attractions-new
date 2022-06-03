<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLocaleRequest;
use App\Http\Requests\UpdateLocaleRequest;
use App\Repositories\LocaleRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class LocaleController extends AppBaseController
{
    /** @var  LocaleRepository */
    private $localeRepository;

    public function __construct(LocaleRepository $localeRepo)
    {
        $this->localeRepository = $localeRepo;
    }

    /**
     * Display a listing of the Locale.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->localeRepository->pushCriteria(new RequestCriteria($request));
        $locales = $this->localeRepository->all();

        return view('locales.index')
            ->with('locales', $locales);
    }

    /**
     * Show the form for creating a new Locale.
     *
     * @return Response
     */
    public function create()
    {
        return view('locales.create');
    }

    /**
     * Store a newly created Locale in storage.
     *
     * @param CreateLocaleRequest $request
     *
     * @return Response
     */
    public function store(CreateLocaleRequest $request)
    {
        $input = $request->all();

        $locale = $this->localeRepository->create($input);

        Flash::success('Locale saved successfully.');

        return redirect(route('locales.index'));
    }

    /**
     * Display the specified Locale.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $locale = $this->localeRepository->findWithoutFail($id);

        if (empty($locale)) {
            Flash::error('Locale not found');

            return redirect(route('locales.index'));
        }

        return view('locales.show')->with('locale', $locale);
    }

    /**
     * Show the form for editing the specified Locale.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $locale = $this->localeRepository->findWithoutFail($id);

        if (empty($locale)) {
            Flash::error('Locale not found');

            return redirect(route('locales.index'));
        }

        return view('locales.edit')->with('locale', $locale);
    }

    /**
     * Update the specified Locale in storage.
     *
     * @param  int              $id
     * @param UpdateLocaleRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLocaleRequest $request)
    {
        $locale = $this->localeRepository->findWithoutFail($id);

        if (empty($locale)) {
            Flash::error('Locale not found');

            return redirect(route('locales.index'));
        }

        $locale = $this->localeRepository->update($request->all(), $id);

        Flash::success('Locale updated successfully.');

        return redirect(route('locales.index'));
    }

    /**
     * Remove the specified Locale from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $locale = $this->localeRepository->findWithoutFail($id);

        if (empty($locale)) {
            Flash::error('Locale not found');

            return redirect(route('locales.index'));
        }

        $this->localeRepository->delete($id);

        Flash::success('Locale deleted successfully.');

        return redirect(route('locales.index'));
    }
}
