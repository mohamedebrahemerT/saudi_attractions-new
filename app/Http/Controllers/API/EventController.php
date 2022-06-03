<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use App\Models\Event;
use App\Transformers\EventDetailsTransformer;
use App\Transformers\EventTransformer;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests\EventNearbyRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class EventController extends ApiController
{


    protected $transformer;
    protected $details_transformer;

    public function __construct(EventTransformer $transformer, EventDetailsTransformer $details_transformer)
    {
        $this->transformer=$transformer;
        $this->details_transformer=$details_transformer;
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events",
     *      summary="List All Events",
     *      tags={"Events"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Events en or ar",
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

    public function listEvents($lang)
    {
        $events_paginate=Event::where('draft', 1)->with('media')->where('end_date', '>=', Carbon::today())->latest()->paginate(9)->toArray();
        $events['events']=$this->transformer->transformCollection($events_paginate['data']);
        return $this->respondAccepted($events);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events/top/list",
     *      summary="List Top Events",
     *      tags={"Events"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Top Events en or ar",
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


    public function listTopEvents($lang)
    {
        $events=Event::where('draft', 1)->with('media')->where('end_date', '>=', Carbon::today())->where(function ($query)
        {
            $query->where('is_featured', 1)->orWhere('week_suggest',1);
        })->take(5)->get()->toArray();
        $events=$this->transformer->transformCollection($events);
        return $this->respondAccepted($events);
    }

    /**
     * @param string
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events/{id}",
     *      summary="List Event Details",
     *      tags={"Events"},
     *      description="",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          name="lang",
     *          description="language of Event en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *    @SWG\Parameter(
     *          name="id",
     *          description="ID of the Event",
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

    public function listEventsDetails($lang, $id)
    {
        $is_liked = false;
        $events=Event::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')->with('gallery')
            ->with('event_days')->with('event_tickets')->with('ticket_dates')->with('tags')->with('tags.media')->with('social_media')->with('social_media.media')->find($id);
        if(!$events){
            return $this->respondNotFound(['msg'=>'Event does not exist']);
        }
        $events= $events->toArray();
        try{
            if ($user = JWTAuth::toUser(JWTAuth::getToken())) {
                if ($user->liked_events->contains($id)) {
                    $is_liked = true;
                }
            }
        }
        catch (JWTException $e) {
        }
        $events=$this->details_transformer->transform($events);
        $events['is_liked']=$is_liked;
        return $this->respondAccepted($events);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/events/nearby/list",
     *      summary="Get Nearby Events",
     *      tags={"Events"},
     *      description="",
     *      produces={"application/json"},
     *      consumes={"application/x-www-form-urlencoded"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Event en or ar",
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


    // public function listNearbyEvents(GetNearbyEventRequest $request, $lang)
    public function listNearbyEvents(Request $request, $lang)
    {
        $event= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM events
            HAVING distance < 10000
            ORDER BY distance
            LIMIT 0 , 20');
        if(!$event){
           // $event= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
           // FROM events
          //  ORDER BY distance
          //  LIMIT 0 , 20');
		return $this->respondNotFound(['msg'=>'Event does not exist']);
        }

        $ids=array_column($event,'id');
        $event=Event::where('draft', 1)->with('media')->whereIn('id',$ids)->where('end_date', '>=', Carbon::today())->get()->toArray();
        $event = $this->transformer->transformCollection($event);

        return $this->respondAccepted($event);
    }

    /**
     * @param string
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events/today/list",
     *      summary="List Today Event",
     *      tags={"Events"},
     *      description="",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          name="lang",
     *          description="language of Today Events en or ar",
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


    public function listTodayEvents($lang)
    {
        $events=Event::where('draft', 1)->with('media')->where('start_date', '<=', Carbon::today())->where('end_date','>=',Carbon::today()->format('Y-m-d'))->get()->toArray();
        $events=$this->transformer->transformCollection($events);
        return $this->respondAccepted($events);
    }

    /**
     * @param string
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events/thisWeek/list",
     *      summary="List This Week Event",
     *      tags={"Events"},
     *      description="",
     *      produces={"application/json"},
     *     @SWG\Parameter(
     *          name="lang",
     *          description="language of This Week Events en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *     @SWG\Parameter(
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


    public function listThisWeekEvents($lang)
    {
        $events=Event::where('draft', 1)->with('media')->where('start_date', '>=', Carbon::today())
            ->where('start_date', '<=', Carbon::today()->addWeek()->format('Y-m-d'))->orWhere(function ($query)
        {
            $query->where('end_date', '>=', Carbon::today())->where('end_date', '<=', Carbon::today()->addWeek()->format('Y-m-d'));
        })->get()->toArray();
        $events=$this->transformer->transformCollection($events);
        return $this->respondAccepted($events);
    }



}
