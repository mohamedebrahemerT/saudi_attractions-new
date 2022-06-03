<?php

namespace App\Http\Controllers\API;

use App\Models\AboutUs;
use App\Transformers\AboutUsTransformer;


class AboutUsController extends ApiController
{
    protected $transformer;

    public function __construct(AboutUsTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/about-us",
     *      summary="List About Us",
     *      tags={"About Us"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of About Us en or ar",
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

    public function listAboutUs($lang)
    {
        $about_us=AboutUs::get()->toArray();
        $about_us=$this->transformer->transformCollection($about_us);
        return $this->respondAccepted($about_us);
    }
}
