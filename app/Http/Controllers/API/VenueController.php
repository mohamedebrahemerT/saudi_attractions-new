<?php

namespace App\Http\Controllers\API;

use App\Models\Venue;
use App\Transformers\VenueDetailsTransformer;
use App\Transformers\VenueTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class VenueController extends ApiController
{
    protected $transformer;
    protected $details_transformer;

    protected $venueValidationRule = [
        'lat' => 'required',
        'lng' => 'required'
    ];

    public function __construct(VenueTransformer $transformer, VenueDetailsTransformer $details_transformer)
    {
        $this->transformer=$transformer;
        $this->details_transformer=$details_transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/venues",
     *      summary="List All Venues",
     *      tags={"Venues"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Venues en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          in="header"
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

    public function listVenues($lang)
    {
        $venues_paginate=Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories')->with('social_media')->latest()->paginate(9)->toArray();
        $venues['venues']=$this->transformer->transformCollection($venues_paginate['data']);
        $venues['first_page_url']=$venues_paginate['first_page_url'];
        $venues['from']=$venues_paginate['from'];
        $venues['last_page']=$venues_paginate['last_page'];
        $venues['next_page_url']=$venues_paginate['next_page_url'];
        $venues['next_page_url']=$venues_paginate['next_page_url'];
        $venues['path']=$venues_paginate['path'];
        $venues['prev_page_url']=$venues_paginate['prev_page_url'];
        $venues['last_page_url']=$venues_paginate['last_page_url'];
        $venues['to']=$venues_paginate['to'];
        $venues['total']=$venues_paginate['total'];
        return $this->respondAccepted($venues);
    }

    /**
     * @param string
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/venues/{id}",
     *      summary="List Venue Details",
     *      tags={"Venues"},
     *      description="",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          name="lang",
     *          description="language of Venue en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *    @SWG\Parameter(
     *          name="id",
     *          description="ID of the Venue",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *    @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          in="header"
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

    public function listVenuesDetails($lang, $id)
    {
        $is_liked = false;
        $venues=Venue::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')->with('gallery')
          ->with('social_media')->with('social_media.media')->with('venue_opening_hours')->with('venue_opening_hours.venue_days')->find($id);
        if(!$venues){
            return $this->respondNotFound(['msg'=>'Venue does not exist']);
        }
        $venues= $venues->toArray();
        try{
        if ($user = JWTAuth::toUser(JWTAuth::getToken())) {
            if ($user->liked_venues->contains($id)) {
                $is_liked = true;
            }
        }
    }
        catch (JWTException $e) {
    }
        $venues=$this->details_transformer->transform($venues);
        $venues['is_liked']=$is_liked;
        return $this->respondAccepted($venues);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/venues/top/list",
     *      summary="List Top Venues",
     *      tags={"Venues"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Top Venues en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          in="header"
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


    public function listTopVenues($lang)
    {
        $venues=Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories')->where('is_featured', 1)->orWhere('is_sponsored', 1)
            ->orWhere('week_suggest', 1)->take(5)->get()->toArray();
        $venues=$this->transformer->transformCollection($venues);
        return $this->respondAccepted($venues);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/venues/nearby/list",
     *      summary="Get Nearby Venues",
     *      tags={"Venues"},
     *      description="",
     *      produces={"application/json"},
     *      consumes={"application/x-www-form-urlencoded"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Venues en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          in="header"
     *      ),
     *        @SWG\Parameter(
     *          name="lat",
     *          description=" Lat of the user",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="lng",
     *          description=" lng of the user",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     * @SWG\Response(
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


    public function listNearbyVenues (Request $request, $lang)
    {
        $validator = Validator::make($request->all(),$this->venueValidationRule);
        if($validator->fails())
        {
            $errors =$validator->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'Errors in inputs','errors'=>$errors]);
        }
        $venue= DB::select('SELECT id, lat,`lng`,
        SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) 
        * COS( lat / 57.3 ) , 2 ) ) AS distance
           FROM venues
           HAVING distance < 20
           ORDER BY distance
           LIMIT 0 , 20');
       if(!$venue){
         #  $venue= DB::select('SELECT id, lat,`lng`,
          # SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) 
          # * COS( lat / 57.3 ) , 2 ) ) AS distance
          #    FROM venues
          #    ORDER BY distance
          #    LIMIT 0 , 20');
        }

        $ids=array_column($venue,'id');
        $venue=Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories')->whereIn('id',$ids)->get()->toArray();
        $venue = $this->transformer->transformCollection($venue);

        return $this->respondAccepted($venue);
    }

}
