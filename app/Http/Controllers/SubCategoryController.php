<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Models\Category;
use App\Models\Locale;
use App\Repositories\SubCategoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\App;
use League\Flysystem\Adapter\Local;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class SubCategoryController extends AppBaseController
{
    /** @var  SubCategoryRepository */
    private $subCategoryRepository;

    public function __construct(SubCategoryRepository $subCategoryRepo)
    {
        $this->subCategoryRepository = $subCategoryRepo;
    }

    /**
     * Display a listing of the SubCategory.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->subCategoryRepository->pushCriteria(new RequestCriteria($request));
        $subCategories = $this->subCategoryRepository->paginate(10);
        $languages=Locale::where('default',1)->get();

        return view('sub_categories.index')
            ->with('subCategories', $subCategories)->with('languages', $languages);
    }

    /**
     * Show the form for creating a new SubCategory.
     *
     * @return Response
     */
    public function create()
    {
        $category = Category::listsTranslations('name')->get()->toArray();
        $category=array_combine(array_column($category,'id'),array_column($category,'name'));
        return view('sub_categories.create')->with('category', $category);
    }

    /**
     * Store a newly created SubCategory in storage.
     *
     * @param CreateSubCategoryRequest $request
     *
     * @return Response
     */
    public function store(CreateSubCategoryRequest $request)
    {
        $input=$request->only('category_id');
        $translation_input=$request->only('name');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);

        $subCategory = $this->subCategoryRepository->create($data);

        Flash::success('Sub Category saved successfully.');

        return redirect(route('subCategories.index'));
    }

    /**
     * Display the specified SubCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $subCategory = $this->subCategoryRepository->findWithoutFail($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }

        return view('sub_categories.show')->with('subCategory', $subCategory);
    }

    /**
     * Show the form for editing the specified SubCategory.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $subCategory = $this->subCategoryRepository->findWithoutFail($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }
        $category = Category::listsTranslations('name')->get()->toArray();
        $category=array_combine(array_column($category,'id'),array_column($category,'name'));
        return view('sub_categories.edit')->with('subCategory', $subCategory)->with('category', $category);
    }

    /**
     * Update the specified SubCategory in storage.
     *
     * @param  int              $id
     * @param UpdateSubCategoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateSubCategoryRequest $request)
    {
        $subCategory = $this->subCategoryRepository->findWithoutFail($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }
        $input=$request->only('category_id');
        $translation_input=$request->only('name');
        $data=[App::getLocale()=>$translation_input];
        $data=array_merge($data,$input);
        $subCategory = $this->subCategoryRepository->update($data, $id);

        Flash::success('Sub Category updated successfully.');

        return redirect(route('subCategories.index'));
    }

    /**
     * Remove the specified SubCategory from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $subCategory = $this->subCategoryRepository->findWithoutFail($id);

        if (empty($subCategory)) {
            Flash::error('Sub Category not found');

            return redirect(route('subCategories.index'));
        }

        $this->subCategoryRepository->delete($id);

        Flash::success('Sub Category deleted successfully.');

        return redirect(route('subCategories.index'));
    }
}
