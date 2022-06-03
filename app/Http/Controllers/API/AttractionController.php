<?php

namespace App\Http\Controllers\API;

use App\Models\Attraction;
use App\Transformers\AttractionDaysTransformer;
use App\Transformers\AttractionDetailsTransformer;
use App\Transformers\AttractionTransformer;
use App\VenueDay;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AttractionController extends ApiController
{
    protected $transformer;
    protected $details_transformer;
    protected $days_transformer;

    protected $attractionValidationRule = [
        'lat' => 'required|numeric',
        'lng' => 'required|numeric'
    ];

    public function __construct(AttractionDaysTransformer $days_transformer, AttractionTransformer $transformer, AttractionDetailsTransformer $details_transformer)
    {
        $this->transformer=$transformer;
        $this->days_transformer=$days_transformer;
        $this->details_transformer=$details_transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions",
     *      summary="List All Attractions",
     *      tags={"Attractions"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Attractions en or ar",
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

    public function listAttractions($lang)
    {
        $attractions_paginate=Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')
           ->with('sub_categories')->latest()->paginate(9)->toArray();
        $attractions['attractions']=$this->transformer->transformCollection($attractions_paginate['data']);
        $attractions['first_page_url']=$attractions_paginate['first_page_url'];
        $attractions['from']=$attractions_paginate['from'];
        $attractions['last_page']=$attractions_paginate['last_page'];
        $attractions['next_page_url']=$attractions_paginate['next_page_url'];
        $attractions['next_page_url']=$attractions_paginate['next_page_url'];
        $attractions['path']=$attractions_paginate['path'];
        $attractions['prev_page_url']=$attractions_paginate['prev_page_url'];
        $attractions['last_page_url']=$attractions_paginate['last_page_url'];
        $attractions['to']=$attractions_paginate['to'];
        $attractions['total']=$attractions_paginate['total'];
        return $this->respondAccepted($attractions);
    }

    /**
     * @param string
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions/{id}",
     *      summary="List Attraction Details",
     *      tags={"Attractions"},
     *      description="",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          name="lang",
     *          description="language of Attraction en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *    @SWG\Parameter(
     *          name="id",
     *          description="ID of the Attraction",
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

    public function listAttractionsDetails($lang, $id)
    {
        $is_liked = false;

        $days=VenueDay::with(['attraction_week_days'=>function($query) use ($id)
        {
            $query->where('attraction_id', $id);
        },'attraction_week_days.types','attraction_week_days.addons'])->get()->toArray();
        $attractions=Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')->with('gallery')
            ->with('social_media')->with('social_media.media')
            ->with('tags')->with('tags.media')->with('attraction_tickets')->with('attraction_addons')
            ->with('attraction_exceptional_dates')->with('attraction_exceptional_dates.types')
            ->with('attraction_exceptional_dates.addons')
            ->find($id);
        if(!$attractions){
            return $this->respondNotFound(['msg'=>'Attraction does not exist']);
        }
        $attractions= $attractions->toArray();
        try{
            if ($user = JWTAuth::toUser(JWTAuth::getToken())) {
                if ($user->liked_attractions->contains($id)) {
                    $is_liked = true;
                }
            }
        }
        catch (JWTException $e) {
        }

        $attractions=$this->details_transformer->transform($attractions);
        $days=$this->days_transformer->transformCollection($days);
        $attractions['is_liked']=$is_liked;
        $attractions['days']=$days;
        return $this->respondAccepted($attractions);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions/top/list",
     *      summary="List Top Attractions",
     *      tags={"Attractions"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Top Attractions en or ar",
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


    public function listTopAttractions($lang)
    {
        $attractions=Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')->where('is_featured', 1)->orWhere('is_sponsored', 1)
            ->orWhere('week_suggest', 1)->take(5)->get()->toArray();
        $attractions=$this->transformer->transformCollection($attractions);
        return $this->respondAccepted($attractions);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/attractions/nearby/list",
     *      summary="Get Nearby Attractions",
     *      tags={"Attractions"},
     *      description="",
     *      produces={"application/json"},
     *      consumes={"application/x-www-form-urlencoded"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Attractions en or ar",
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


    public function listNearbyAttractions (Request $request, $lang)
    {
        $validator = Validator::make($request->all(),$this->attractionValidationRule);
        if($validator->fails())
        {
            $errors =$validator->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'Errors in inputs','errors'=>$errors]);
        }

        $attraction= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
        FROM attractions
        HAVING distance < 10000
        ORDER BY distance
        LIMIT 0 , 20');
        if(!$attraction){
            $attraction= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM attractions
            ORDER BY distance
            LIMIT 0 , 20');
        }
    
        $ids=array_column($attraction,'id');
        $attraction=Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')->whereIn('id',$ids)->get()->toArray();
        $attraction = $this->transformer->transformCollection($attraction);

        return $this->respondAccepted($attraction);
    }
}
