<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends ApiController
{
    protected $transformer;

    public function __construct(CategoryTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/categories",
     *      summary="List Categories",
     *      tags={"Categories"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Categories en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *               @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                    @SWG\Items(
     *                   @SWG\Property(property="msg", type="string"),
     *                 )
     *              ),
     *          )
     *      )
     * )
     */

    public function listCategories($lang)
    {
        $categories=Category::with('media')->translatedIn($lang)->latest()->get()->toArray();
        $categories=$this->transformer->transformCollection($categories);
        return $this->respondAccepted($categories);
    }
}
