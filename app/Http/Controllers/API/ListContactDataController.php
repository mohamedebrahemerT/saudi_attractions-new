<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\ContactDataTransformer;
use App\Models\ContactUs;


class ListContactDataController extends ApiController
{
    protected $transformer;

    public function __construct(ContactDataTransformer $transformer)
    {
        $this->transformer=$transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/contact/data",
     *      summary="List Contact Us Data",
     *      tags={"Contact Us"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language en or ar",
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

    public function listContactData($lang)
    {
        $contactData=ContactUs::with('contactMedia')->with('contactMedia.media')->translatedIn($lang)->get()->toArray();
        $contactData=$this->transformer->transformCollection($contactData);
        return $this->respondAccepted($contactData);
    }
}
