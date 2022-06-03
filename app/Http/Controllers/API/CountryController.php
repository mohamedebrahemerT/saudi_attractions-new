<?php

namespace App\Http\Controllers\API;

use App\Country;
use App\Transformers\CountryTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends ApiController
{
    protected $transformer;

    public function __construct(CountryTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/countries",
     *      summary="List Countries and its cities",
     *      tags={"Countries"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Countries en or ar",
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

    public function listCountries($lang)
    {
        $countries=Country::with('cities')->latest()->get()->toArray();
        $countries=$this->transformer->transformCollection($countries);
        return $this->respondAccepted($countries);
    }
}
