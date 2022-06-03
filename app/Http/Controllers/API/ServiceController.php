<?php

namespace App\Http\Controllers\API;

use App\AttractionAddon;
use App\AttractionExceptionalDate;
use App\AttractionTicket;
use App\AttractionTicketNumber;
use App\AttractionWeekDay;
use App\ContactUs;
use App\EventTicket;
use App\Models\Attraction;
use App\Models\Event;
use App\Models\SocialMedia;
use App\Models\Venue;
use App\Order;
use App\OrderAttraction;
use App\TicketDate;
use App\Transformers\AttractionNearbyTransformer;
use App\Transformers\AttractionTicketViewTransformer;
use App\Transformers\AttractionTransformer;
use App\Transformers\EventNearbyTransformer;
use App\Transformers\EventTicketTransformer;
use App\Transformers\EventTransformer;
use App\Transformers\LikedEventTransformer;
use App\Transformers\OrderAttractionTransformer;
use App\Transformers\OrderTransformer;
use App\Transformers\TicketDateTransformer;
use App\Transformers\TicketViewTransformer;
use App\Transformers\VenueNearbyTransformer;
use App\Transformers\VenueTransformer;
use App\UserAttractionTicket;
use App\UserTicket;
use App\VenueDay;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use \Illuminate\Support\Facades\Validator;

class ServiceController extends ApiController
{


    protected $ticket_transformer;
    protected $date_transformer;
    protected $order_transformer;
    protected $venue_transformer;
    protected $attraction_transformer;
    protected $transformer;
    protected $venue_nearby_transformer;
    protected $attraction_nearby_transformer;
    protected $event_nearby_transformer;
    protected $order_attraction_transformer;
    protected $ticket_view_transformer;


    protected $orderValidationRules = [

        'name' => 'required',
        'email' => 'email|required',
        'mobile_number' => 'required|numeric|digits_between:6,12',
        'number_of_tickets' => 'required|numeric|min:0',
        'payment_method' => 'required|in:cash_on_delivery,pay_later,credit_card',
        'event_id' => 'required',
        'event_ticket_id' => 'required',
        'ticket_date_id' => 'required',
        'user_national_id' => 'numeric|digits_between:14,22',
        'address' => 'required'
    ];

    protected $ticketViewValidationRules = [

        'start_time' => 'required',
        'end_time' => 'required',
        'date' => 'date|required',
        'attraction_id' => 'numeric|required',
    ];


    protected $orderAttractionValidationRules = [

        'name' => 'required',
        'email' => 'email|required',
        'mobile_number' => 'required|numeric|digits_between:6,12',
        'payment_method' => 'required|in:cash_on_delivery,pay_later,credit_card',
        'attraction_id' => 'required',
        'user_national_id' => 'numeric|digits_between:14,22',
        'attraction_week_day_id'=>'required_without_all:exceptional_date_id',
        'exceptional_date_id'=>'required_without_all:attraction_week_day_id',
        'address' => 'required'
    ];



    protected $filterValidationRules = [

        'lat' => 'required_with:lng|numeric',
        'lng' => 'required_with:lat|numeric'
    ];

    protected $contactValidationRules = [

        'subject' => 'required',
        'message' => 'required'
    ];

    protected $nearbyValidationRule = [

        'lat' => 'required|numeric',
        'lng' => 'required|numeric'
    ];



    public function __construct(EventTicketTransformer $ticket_transformer, TicketDateTransformer $date_transformer, OrderTransformer $order_transformer,
                                EventTransformer $transformer, VenueTransformer $venue_transformer, AttractionTransformer $attraction_transformer,
                                EventNearbyTransformer $event_nearby_transformer, VenueNearbyTransformer $venue_nearby_transformer,
                                AttractionNearbyTransformer $attraction_nearby_transformer,
                                OrderAttractionTransformer $order_attraction_transformer, AttractionTicketViewTransformer $ticket_view_transformer)
    {
        $this->ticket_transformer = $ticket_transformer;
        $this->date_transformer = $date_transformer;
        $this->order_transformer = $order_transformer;
        $this->transformer = $transformer;
        $this->venue_transformer =$venue_transformer;
        $this->attraction_transformer =$attraction_transformer;
        $this->event_nearby_transformer = $event_nearby_transformer;
        $this->venue_nearby_transformer =$venue_nearby_transformer;
        $this->attraction_nearby_transformer =$attraction_nearby_transformer;
        $this->ticket_view_transformer =$ticket_view_transformer;
        $this->order_attraction_transformer =$order_attraction_transformer;

    }


    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events/{id}/like",
     *      summary="Like/Unlike Events",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of event en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID Of the Event",
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

    public function likeEvent($lang, $id)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        $event = Event::where('draft', 1)->find($id);
        if (!$event) {
            return $this->respondNotFound(['msg' => 'Event does not exist']);
        }
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        if ($user->liked_events->contains($id)) {
            $user->liked_events()->detach($id);
            Event::where('draft', 1)->find($id)->decrement('number_of_likes');
            $message = "Unliked";
        } else {
            $user->liked_events()->attach($id);
            Event::where('draft', 1)->find($id)->increment('number_of_likes');
            $message = "Liked";
        }

        return $this->respondAccepted(['msg' => 'Event has been ' . $message . ' successfully']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/events/ticket/order",
     *      summary="Order Tickets",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"multipart/form-data"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *        @SWG\Parameter(
     *          name="name",
     *          description=" Name of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="email",
     *          description=" Email of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="mobile_number",
     *          description=" Mobile Number of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="number_of_tickets",
     *          description=" Number Of Tickets ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="payment_method",
     *          description=" Payment Method: pay_later , cash_on_delivery or credit_card",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="event_id",
     *          description=" ID of the Event ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="event_ticket_id",
     *          description=" ID of the Ticket ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="ticket_date_id",
     *          description=" ID of Date of the Ticket ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *        @SWG\Parameter(
     *          name="user_national_id",
     *          description=" National ID of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *        @SWG\Parameter(
     *          name="address",
     *          description=" Address of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="total",
     *          description=" Amount of Price of Tickets ",
     *          type="string",
     *          in="formData"
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

    public function orderTickets(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());

        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }

        $order_validation = Validator::make($request->all(), $this->orderValidationRules);

        if ($order_validation->fails()) {
            $errors = $order_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $input = $request->only('name', 'email', 'mobile_number', 'number_of_tickets', 'payment_method', 'event_id', 'event_ticket_id',
            'ticket_date_id', 'total', 'user_national_id', 'address');

        $events = Event::where('draft', 1)->find($request->input('event_id'));

        if (!$events) {
            return $this->respondNotFound(['msg' => 'Event does not exist']);
        }
        $tickets = EventTicket::find($request->input('event_ticket_id'));

        if (!$tickets) {
            return $this->respondNotFound(['msg' => 'Ticket does not exist']);
        }

        $dates = TicketDate::find($request->input('ticket_date_id'));

        if (!$dates) {
            return $this->respondNotFound(['msg' => 'Ticket Date does not exist']);
        }

        if ($request->input('number_of_tickets') > $tickets->number_of_tickets) {
            return $this->respondAccepted(['msg' => 'Number of tickets should be less than ' . $tickets->number_of_tickets . '']);
        }

        if ($events->national_id == 1) {
            if ((empty($request->input('user_national_id')))) {
                $national_id_validation = Validator::make($request->all(), [
                    'user_national_id' => 'required'
        ]);

        if ($national_id_validation->fails()) {
            $errors = $national_id_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }
            }
        }

        $input['user_id'] = $user->id;
        $input['order_number'] = rand(1111111, 999999);
        $order = Order::create($input);
        Event::where('draft', 1)->find($request->input('event_id'))->increment('number_of_going');
        EventTicket::find($request->input('event_ticket_id'))->decrement('number_of_tickets');
        for ($i = 1; $i <= ($input['number_of_tickets']); $i++) {
            UserTicket::create(['user_id' => $user->id, 'order_id' => $order->id, 'event_ticket_id' => $tickets->id, 'ticket_number' => rand(1111111, 999999)]);
        }
        $tickets_id = UserTicket::all()->where('order_id', $order->id);

        return $this->respondAccepted(['order' => $order]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/payment/event/change",
     *      summary="change Payment Status",
     *      tags={"change Payment Status"},
     *      description="change Payment Of Event Order Status",
     *      produces={"application/json"},
     *      consumes={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="status",
     *          in="formData",
     *          type="string",
     *          description="status 1 or 2",
     *          required=true,
     *      ),
     *       @SWG\Parameter(
     *          name="order_id",
     *          in="formData",
     *          type="string",
     *          description="order_id",
     *          required=true,
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
     *              @SWG\Property(
     *                  property="data",
     *                  type="string",
     *              )
     *          )
     *      )
     * )
     */


    public function changeStatusEvent(Request $request)
    {
        $user_id = JWTAuth::toUser(JWTAuth::getToken())->id;
        $order = Order::where('id',$request->input('order_id'))->where('user_id',$user_id)->update(['status'=>$request->input('status')]);

        if(!$order){
           return $this->respondNotAcceptable('user not authenticated');
        }
$order_info = Order::where('id',$request->input('order_id'))->get();
        $user_email = JWTAuth::toUser(JWTAuth::getToken())->email;
Mail::send('emails.confirm',[
            'event_image'=>$order_info[0]->event->media->image,
            'event_name'=>$order_info[0]->event->english_notification_title,
            'event_date'=>$order_info[0]->ticket_date->date,
            'event_time'=>$order_info[0]->ticket_date->time,
            'total'=>$order_info[0]->total,
            'event_location'=>$order_info[0]->event->address_url,
            'name'=> $order_info[0]->name,
            'mobile_number'=>$order_info[0]->mobile_number,
            'payment_method'=>$order_info[0]->payment_method,
            'order_number'=>$order_info[0]->order_number,
            'number_of_tickets'=>$order_info[0]->number_of_tickets,
            'tickets_id'=>$order_info[0]->event_ticket->translations,
            'event_social'=>$order_info[0]->event->social_media
        
        ], function ($message) use ($request)
        {
            $message->from('no-reply@saudiattractions.net', 'Saudi Attraction');

            $message->subject("Confirmaton Saudi Attraction Event");

            $message->to(JWTAuth::toUser(JWTAuth::getToken())->email);

        });


        return $this->respondAccepted('status changed successfully');
    }


    /**
     * @param string $lang
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/events/order/cancel/{id}",
     *      summary="Cancel Order",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID Of the Order",
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

    public function cancelOrder($lang, $id)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());

        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }

        $order = Order::find($id);
        if (!$order) {
            return $this->respondNotFound(['msg' => 'Order does not exist']);
        }

        if ($order->payment_method == "credit_card") {
            return $this->respondNotAcceptable(['msg' => 'You cannot cancel an online order']);
        }
        if ($user->orders->contains($id)) {
                Event::find($order['event_id'])->decrement('number_of_going');
                EventTicket::find($order['event_ticket_id'])->increment('number_of_tickets');
                $order->is_canceled = 1;
                $order->save();
                return $this->respondAccepted(['msg' => 'Order has been canceled successfully']);
        }
        return $this->respondAccepted(['msg' => 'Order does not found']);

    }


    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/history/orders/upcoming",
     *      summary="View User's Upcoming Tickets",
     *      tags={"History"},
     *      description="History of Upcoming Tickets of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewUpcomingOrderHistory($lang)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg' => 'User is blocked']);
        }

        $orders = Order::whereHas('event', function ($query) {
            $query->where('end_date', '>=', Carbon::today());
        })->with('event')->with('event.media')->with('event_ticket')->with('ticket_date')->where('user_id', $user->id)->get()->toArray();
        $orders = $this->order_transformer->transformCollection($orders);

        return $this->respondAccepted($orders);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/history/orders/past",
     *      summary="View User's Past Tickets",
     *      tags={"History"},
     *      description="History of Past Tickets of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewPastOrderHistory($lang)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg' => 'User is blocked']);
        }
        $orders = Order::whereHas('event', function ($query) {
            $query->where('end_date', '<=', Carbon::today());
        })->with('event')->with('event.media')->with('event_ticket')->with('ticket_date')->where('user_id', $user->id)
           ->get()->toArray();
        $orders = $this->order_transformer->transformCollection($orders);

        return $this->respondAccepted($orders);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/history/liked",
     *      summary="View User's Liked Events",
     *      tags={"History"},
     *      description="History of liked Events of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewLikedHistory($lang)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());

        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }

        $liked_events = $user->liked_events()->with('media')->get()->toArray();
        $liked_events = $this->transformer->transformCollection($liked_events);
        return $this->respondAccepted($liked_events);
    }

    public function cronJob($lang)
    {
        $orders = Order::where('created_at', '<', Carbon::now()->subHours(5))->where('status', 0)->where('payment_method', '!=', 'credit_card');
        $orders->update(['status' => 2]);
        return $this->respondAccepted(['msg' => 'Order rejected successfully']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/venues/{id}/like",
     *      summary="Like/Unlike Venues",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of venue en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID Of the Venue",
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

    public function likeVenue($lang, $id)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $venue = Venue::where('draft', 1)->find($id);
        if (!$venue) {
            return $this->respondNotFound(['msg' => 'Venue does not exist']);
        }
        if ($user->liked_venues->contains($id)) {
            $user->liked_venues()->detach($id);
            Venue::where('draft', 1)->find($id)->decrement('number_of_likes');
            $message = "Unliked";
        } else {
            $user->liked_venues()->attach($id);
            Venue::where('draft', 1)->find($id)->increment('number_of_likes');
            $message = "Liked";
        }

        return $this->respondAccepted(['msg' => 'Venue has been ' . $message . ' successfully']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/venues/liked/list",
     *      summary="View User's Liked Venues",
     *      tags={"History"},
     *      description="History of liked Venues of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewLikedVenues($lang)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $liked_venues = $user->liked_venues()->with('media')->with('categories')->with('sub_categories')->get()->toArray();
        $liked_venues = $this->venue_transformer->transformCollection($liked_venues);
        return $this->respondAccepted($liked_venues);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions/{id}/like",
     *      summary="Like/Unlike Attractions",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of attraction en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID Of the Attraction",
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

    public function likeAttraction($lang, $id)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $attraction = Attraction::where('draft', 1)->find($id);
        if (!$attraction) {
            return $this->respondNotFound(['msg' => 'Attraction does not exist']);
        }
        if ($user->liked_attractions->contains($id)) {
            $user->liked_attractions()->detach($id);
            Attraction::where('draft', 1)->find($id)->decrement('number_of_likes');
            $message = "Unliked";
        } else {
            $user->liked_attractions()->attach($id);
            Attraction::where('draft', 1)->find($id)->increment('number_of_likes');
            $message = "Liked";
        }

        return $this->respondAccepted(['msg' => 'Attraction has been ' . $message . ' successfully']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions/liked/list",
     *      summary="View User's Liked Attractions",
     *      tags={"History"},
     *      description="History of liked Attractions of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewLikedAttractions($lang)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $liked_attractions = $user->liked_attractions()->with('media')->with('categories')->with('categories.media')->with('sub_categories')->get()->toArray();
        $liked_attractions = $this->attraction_transformer->transformCollection($liked_attractions);
        return $this->respondAccepted($liked_attractions);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/search/filter",
     *      summary="Search by filters",
     *      tags={"Search"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of filter en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
    *        @SWG\Parameter(
     *          name="search",
     *          description="title or description of any type: Events, Venues and Attractions",
     *          type="string",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="type",
     *          description="types: [venues, events, attractions].",
     *          type="string",
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="category",
     *          description="category: [1, 2, 3], 1 => Entertainments, 2 => Restaurants,  3 => Lounges.",
     *          type="string",
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


    public function searchFilter($lang, Request $request)
    {
        $types = $request->input('types');
        $events = Event::where('draft', 1)->with('media')->with('categories')->where('end_date', '>=', Carbon::today());
        $venues = Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories');
        $attractions = Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories');

        if($request->input('search')){

            $events=$events->whereTranslationLike('title',  '%' . $request->input('search') . '%')->orWhereTranslationLike('description', '%' . $request->input('search') . '%');

            $venues=$venues->whereTranslationLike('title', '%' . $request->input('search') . '%')->orWhereTranslationLike('description', '%' . $request->input('search') . '%');

            $attractions=$attractions->whereTranslationLike('title', '%' . $request->input('search') . '%')->orWhereTranslationLike('description', '%' . $request->input('search') . '%');

        }

        if($request->input('category')){
            $category =  $request->input('category');

            $events->whereHas('categories',  function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

            $venues->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

            $attractions->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });
        }

        $events= $events->get()->toArray();
        $venues= $venues->get()->toArray();
        $attractions=$attractions->get()->toArray();


        if($types){
            if(! in_array('events', $types)){
                $events = [];
            }

            if(! in_array('venues', $types)){
                $venues = [];
            }

            if(! in_array('attractions', $types)){
                $attractions = [];
            }
        }

        $events=$this->transformer->transformCollection($events);
        $venues=$this->venue_transformer->transformCollection($venues);
        $attractions=$this->attraction_transformer->transformCollection($attractions);
        $result=array_merge($events,$venues,$attractions);
        usort($result, [$this,'sortByOrder']);
        if(!$result) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($result)]);

    }

    function sortByOrder($a, $b) {
        return $a['created_at'] - $b['created_at'];
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/nearby/all/list",
     *      summary="Get Nearby Venues, Events and Attractions",
     *      tags={"Requests"},
     *      description="",
     *      produces={"application/json"},
     *      consumes={"application/x-www-form-urlencoded"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Venues, Events or Attractions en or ar",
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


    public function listNearbyAll(Request $request, $lang)
    {
        $validator = Validator::make($request->all(),$this->nearbyValidationRule);
        if($validator->fails())
        {
            $errors =$validator->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'Errors in inputs','errors'=>$errors]);
        }
        $events= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM events
            HAVING distance < 20
            ORDER BY distance
            LIMIT 0 , 20');
        if(!$events){
 #           $events= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
 #           FROM events
 #           ORDER BY distance
 #           LIMIT 0 , 20');
            }
        $ids=array_column($events,'id');
        $events=Event::where('draft', 1)->with('media')->with('categories')->with('sub_categories')->whereIn('id',$ids)->where('end_date', '>=', Carbon::today())->get()->toArray();


        $venues= DB::select('SELECT id, lat,`lng`,
         SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng )
         * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM venues
            HAVING distance < 20
            ORDER BY distance
            LIMIT 0 , 20');
        if(!$venues){
#            $venues= DB::select('SELECT id, lat,`lng`,
#            SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng )
#            * COS( lat / 57.3 ) , 2 ) ) AS distance
#               FROM venues
#               ORDER BY distance
#               LIMIT 0 , 20');
        }
        $ids=array_column($venues,'id');
        $venues=Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories')->whereIn('id',$ids)->get()->toArray();



        $attractions= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM attractions
            HAVING distance < 20
            ORDER BY distance
            LIMIT 0 , 20');
        if(!$attractions){
            #$attractions= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            #FROM attractions
            #ORDER BY distance
            #LIMIT 0 , 20');
#$attractions = "No ";
        }
        $ids=array_column($attractions,'id');
        $attractions=Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories')->whereIn('id',$ids)->get()->toArray();


        $events = $this->event_nearby_transformer->transformCollection($events);
        $venues = $this->venue_nearby_transformer->transformCollection($venues);
        $attractions = $this->attraction_nearby_transformer->transformCollection($attractions);
        $result=array_merge($events,$venues,$attractions);
        usort($result, [$this,'sortByOrder']);
        if(!$result) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($result)]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/users/subscribe",
     *      summary="Subscribe to Newsletter",
     *      tags={"Newsletter"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Newsletter en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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

    public function subscribe($lang)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $user->update(['flag'=>1]);

        return $this->respondAccepted(['msg' => 'User has been Subscribed successfully ']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/users/unsubscribe",
     *      summary="Unsubscribe to Newsletter",
     *      tags={"Newsletter"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Newsletter en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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

    public function unSubscribe($lang)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $user->update(['flag'=>0]);

        return $this->respondAccepted(['msg' => 'User has been Unsubscribed successfully ']);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/filter/venue",
     *      summary="Filter Venues",
     *      tags={"Search"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of result en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *        @SWG\Parameter(
     *          name="week_suggest",
     *          description="Weekly Suggestions of the venues, [true or false]",
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="category",
     *          description="Category: [1, 2, 3], 1 => Entertainments, 2 => Restaurants,  3 => Lounges.",
     *          type="string",
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="lat",
     *          description=" Lat of the user",
     *          type="string",
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="lng",
     *          description=" lng of the user",
     *          type="string",
     *          in="formData"
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

    public function filterVenue($lang, Request $request)
    {
        $filter_validation = Validator::make($request->all(), $this->filterValidationRules);

        if ($filter_validation->fails()) {
            $errors = $filter_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $venues = Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories');

        if($request->input('week_suggest')){
            if($request->input('week_suggest') == true) {
                $venues = $venues->where('week_suggest', 1);
            }
        }
        if($request->input('lat') && $request->input('lng') ){
            $venues_nearby= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM venues
            HAVING distance < 10000
            ORDER BY distance
            LIMIT 0 , 20');
            if(!$venues_nearby){
                $venues_nearby= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
                FROM venues
                ORDER BY distance
                LIMIT 0 , 20');
            }
            $ids=array_column($venues_nearby,'id');
            $venues->whereIn('id',$ids);
        }
        if($request->input('category')){
            $category =  $request->input('category');
            $venues->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

        }
        $venues= $venues->get()->toArray();
        $venues=$this->venue_transformer->transformCollection($venues);
        usort($venues, [$this,'sortByOrder']);
        if(!$venues) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($venues)]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/filter/event",
     *      summary="Filter Events",
     *      tags={"Search"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of result en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *        @SWG\Parameter(
     *          name="week_suggest",
     *          description="Weekly Suggestions of the events, [true or false]",
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="category",
     *          description="Category: [1, 2, 3], 1 => Entertainments, 2 => Restaurants,  3 => Lounges.",
     *          type="string",
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="date",
     *          description="Month of start date or end date of the events",
     *          type="string",
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="lat",
     *          description=" Lat of the user",
     *          type="string",
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="lng",
     *          description=" lng of the user",
     *          type="string",
     *          in="formData"
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

    public function filterEvent($lang, Request $request)
    {
        $filter_validation = Validator::make($request->all(), $this->filterValidationRules);

        if ($filter_validation->fails()) {
            $errors = $filter_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $events = Event::where('draft', 1)->with('media')->with('categories')->with('sub_categories')->where('end_date', '>=', Carbon::today());

        if($request->input('week_suggest')){
            if($request->input('week_suggest') == true) {
                $events = $events->where('week_suggest', 1);
            }
        }

        if($request->input('date')){
            $date = $request->input('date');
            $events->where(function ($query) use ($date) {
                    return $query->whereMonth('start_date', $date)->orWhere(function ($query) use ($date)
                    {
                        $query->whereMonth('end_date',$date);
                    });
                });
        }

        if($request->input('lat') && $request->input('lng') ){
            $events_nearby= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM events
            HAVING distance < 10000
            ORDER BY distance
            LIMIT 0 , 20');
            if(!$events_nearby){
                $events_nearby= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
                FROM events
                ORDER BY distance
                LIMIT 0 , 20');
            }
            $ids=array_column($events_nearby,'id');
            $events->whereIn('id',$ids);
        }
        if($request->input('category')){
            $category =  $request->input('category');
            $events->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

        }
        $events= $events->get()->toArray();
        $events=$this->transformer->transformCollection($events);
        usort($events, [$this,'sortByOrder']);
        if(!$events) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($events)]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/filter/attraction",
     *      summary="Filter Attractions",
     *      tags={"Search"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of result en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *        @SWG\Parameter(
     *          name="week_suggest",
     *          description="Weekly Suggestions of the attractions, [true or false]",
     *          type="string",
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="category",
     *          description="Category: [1, 2, 3], 1 => Entertainments, 2 => Restaurants,  3 => Lounges.",
     *          type="string",
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="lat",
     *          description=" Lat of the user",
     *          type="string",
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="lng",
     *          description=" lng of the user",
     *          type="string",
     *          in="formData"
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

    public function filterAttraction($lang, Request $request)
    {
        $filter_validation = Validator::make($request->all(), $this->filterValidationRules);

        if ($filter_validation->fails()) {
            $errors = $filter_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $attractions = Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories');

        if($request->input('week_suggest')){
            if($request->input('week_suggest') == true) {
                $attractions = $attractions->where('week_suggest', 1);
            }
        }
        if($request->input('lat') && $request->input('lng') ){
            $attractions_nearby= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
            FROM attractions
            HAVING distance < 10000
            ORDER BY distance
            LIMIT 0 , 20');
            if($attractions_nearby){
                $attractions_nearby= DB::select('SELECT id, lat,`lng`, SQRT( POW( 69.1 * ( lat - '.$request->input('lat').' ) , 2 ) + POW( 69.1 * ( '.$request->input('lng').' - lng ) * COS( lat / 57.3 ) , 2 ) ) AS distance
                FROM attractions
                ORDER BY distance
                LIMIT 0 , 20');
            }
            $ids=array_column($attractions_nearby,'id');
            $attractions->whereIn('id',$ids);
        }
        if($request->input('category')){
            $category =  $request->input('category');
            $attractions->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

        }
        $attractions= $attractions->get()->toArray();
        $attractions=$this->attraction_transformer->transformCollection($attractions);
        usort($attractions, [$this,'sortByOrder']);
        if(!$attractions) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($attractions)]);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/contact",
     *      summary="Contact Us",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"multipart/form-data"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of contact en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *        @SWG\Parameter(
     *          name="subject",
     *          description="Subject of the contact us form",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="message",
     *          description="Message of the contact us form",
     *          type="string",
     *          required=true,
     *          in="formData"
     *        ),
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

    public function contactUs(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $contact_validation = Validator::make($request->all(),$this->contactValidationRules);

        if ($contact_validation->fails()) {
            $errors = $contact_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }
        $input = $request->only('subject' ,'message');

        $input['user_id'] = $user->id;

        $contact_us = ContactUs::create($input);

        Mail::send('emails.contact', [
            'user'    => $user->name,
            'subject' => $contact_us->subject,
            'user_message' => $contact_us->message,

        ], function ($message) use ($user) {

            $message->from('info@saudiattractions.net', 'Saudi Attractions');

            $message->subject("Contact Us");

            $message->to('doha.mamdouh@spade.studio');
        });

        return $this->respondAccepted(['msg'=>'Message sent successfully']);
    }


    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/attractions/ticket/order",
     *      summary="Order Attraction Tickets",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"multipart/form-data"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *        @SWG\Parameter(
     *          name="name",
     *          description=" Name of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="email",
     *          description=" Email of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *     @SWG\Parameter(
     *          name="mobile_number",
     *          description=" Mobile Number of the User ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="tickets",
     *          description=" Ticket Types and Add-ons , [{'attraction_ticket_id':1, 'attraction_addon_id':null,
     *         'number':3}, {'attraction_ticket_id':null, 'attraction_addon_id':1, 'number':2}] ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *      @SWG\Parameter(
     *          name="payment_method",
     *          description=" Payment Method: pay_later , cash_on_delivery or credit_card",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="attraction_id",
     *          description=" ID of the Attraction ",
     *          type="string",
     *          required=true,
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="exceptional_date_id",
     *          description=" ID of the Exceptional Date and Time ",
     *          type="string",
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="attraction_week_day_id",
     *          description=" ID of the Option Date and Time ",
     *          type="string",
     *          in="formData"
     *      ),
     *        @SWG\Parameter(
     *          name="user_national_id",
     *          description=" National ID of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *        @SWG\Parameter(
     *          name="address",
     *          description=" Address of the User ",
     *          type="string",
     *          in="formData"
     *      ),
     *       @SWG\Parameter(
     *          name="total",
     *          description=" Amount of Price of Tickets ",
     *          type="string",
     *          in="formData"
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

    public function orderAttractions(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());

        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }

        $order_attraction_validation = Validator::make($request->all(), $this->orderAttractionValidationRules);

        if ($order_attraction_validation->fails()) {
            $errors = $order_attraction_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }

        $input = $request->only('name', 'email', 'mobile_number', 'payment_method', 'attraction_id', 'total', 'user_national_id', 'address');



        $attractions = Attraction::where('draft', 1)->find($request->input('attraction_id'));

        if (!$attractions) {
            return $this->respondNotFound(['msg' => 'Attraction does not exist']);
        }


        if ($attractions->national_id == 1) {
            if ((empty($request->input('user_national_id')))) {
                $national_id_validation = Validator::make($request->all(), [
                    'user_national_id' => 'required'
                ]);

                if ($national_id_validation->fails()) {
                    $errors = $national_id_validation->messages()->toArray();
                    $errors= call_user_func_array('array_merge', $errors);
                    return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
                }
            }
        }

        $input['user_id'] = $user->id;
        $input['order_number'] = rand(1111111, 999999);
        if($request->input('exceptional_date_id')){
            $exceptional = AttractionExceptionalDate::where('id', $request->input('exceptional_date_id'))->first();
            $input['date'] = $exceptional->date;
        }
        else{
            $option = AttractionWeekDay::where('id', $request->input('attraction_week_day_id'))->first();
            $day = VenueDay::where('id', $option->venue_day_id)->first();
            $date = Carbon::parse($day->day)->format('Y-m-d');
            $input['date'] = $date;
        }


        $order = OrderAttraction::create($input);


        Attraction::where('draft', 1)->find($request->input('attraction_id'))->increment('number_of_going');

        $tickets=[];
        if($request->input('tickets')) {
            foreach ($request->input('tickets') as $ticket) {
                if ($ticket['attraction_ticket_id']) {
                    for ($i = 1; $i <= ($ticket['number']); $i++) {
                        AttractionTicketNumber::create(['user_id' => $user->id, 'order_id' => $order->id, 'attraction_ticket_id' => $ticket['attraction_ticket_id'], 'ticket_number' => rand(1111111, 999999)]);
                    }
                }
                $ticket = UserAttractionTicket::create(['ticket_number' => rand(1111111, 999999), 'attraction_ticket_id' => $ticket['attraction_ticket_id'], 'attraction_addon_id' => $ticket['attraction_addon_id'],
                    'number_of_tickets' => $ticket['number'], 'attraction_week_day_id' => $request->input('attraction_week_day_id'),
                    'attraction_exceptional_date_id' => $request->input('exceptional_date_id'), 'order_id' => $order->id, 'user_id' => $user->id]);
                $ticket->decrement('number_of_tickets');
                $tickets[] = $ticket;

                $tickets_id = AttractionTicketNumber::all()->where('order_id', $order->id);
                if ($ticket['attraction_ticket_id']) {
                    $tickets = UserAttractionTicket::all()->where('order_id', $order->id);
                }
              }

          }



        return $this->respondAccepted(['order' => $order]);
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/payment/attraction/change",
     *      summary="change Payment Status",
     *      tags={"change Payment Status"},
     *      description="change Payment Of Attraction Order Status",
     *      produces={"application/json"},
     *      consumes={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of Order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="status",
     *          in="formData",
     *          type="string",
     *          description="status 1 or 2",
     *          required=true,
     *      ),
     *       @SWG\Parameter(
     *          name="order_id",
     *          in="formData",
     *          type="string",
     *          description="order_id",
     *          required=true,
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
     *              @SWG\Property(
     *                  property="data",
     *                  type="string",
     *              )
     *          )
     *      )
     * )
     */


    public function changeStatusAttraction(Request $request)
    {
        $user_id = JWTAuth::toUser(JWTAuth::getToken())->id;
        $order = OrderAttraction::where('id',$request->input('order_id'))->where('user_id',$user_id)->update(['status'=>$request->input('status')]);
        if(!$order){
            return $this->respondNotAcceptable('user not authenticated');
        }
        return $this->respondAccepted('status changed successfully');
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions/ticket/view",
     *      summary="View types and add-ons that matches the date and time",
     *      tags={"Requests"},
     *      description="",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *     @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *     @SWG\Parameter(
     *          name="attraction_id",
     *          description="attraction ID'",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *     @SWG\Parameter(
     *          name="start_time",
     *          description="Start time of the attraction in format like this '16:30:00'",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *     @SWG\Parameter(
     *          name="end_time",
     *          description="End time of the attraction in format like this '16:30:00'",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *
     *     @SWG\Parameter(
     *          name="date",
     *          description="Date of the attraction in format like this '2018-03-12' ",
     *          type="string",
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


    public function pickTicketsView(Request $request)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }
        $ticket_view_validation = Validator::make($request->all(),$this->ticketViewValidationRules);

        if ($ticket_view_validation->fails()) {
            $errors = $ticket_view_validation->messages()->toArray();
            $errors= call_user_func_array('array_merge', $errors);
            return $this->respondNotAcceptable(['msg'=>'error in input ','errors'=>$errors]);
        }
        $input = $request->only('start_time' ,'end_time', 'date', 'attraction_id');

        $tickets = AttractionExceptionalDate::with('types')->with('addons')->with('attractions')
            ->where('start_time', $request->input('start_time'))->where('date', $request->input('date'))
            ->where('end_time', $request->input('end_time'))
            ->where('attraction_id', $request->input('attraction_id'))->get()->toArray();

        if($tickets == null){
            $day = Carbon::parse($request->input('date'))->format('l');
            $date = VenueDay::where('day', $day)->first();
            $tickets = AttractionWeekDay::with('types')->with('addons')->with('attractions')
            ->where('venue_day_id', $date->id)->where('start_time', $request->input('start_time'))->where('end_time', $request->input('end_time'))
                ->where('attraction_id', $request->input('attraction_id'))->get()->toArray();

        }

        $tickets=$this->ticket_view_transformer->transformCollection($tickets);

        return $this->respondAccepted($tickets);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/attractions/order/cancel/{id}",
     *      summary="Cancel Attraction Order",
     *      tags={"Requests"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of order en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
     *          in="header"
     *      ),
     *      @SWG\Parameter(
     *          name="id",
     *          description="ID Of the Order",
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

    public function cancelAttractionOrder($lang, $id)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());

        if($user['is_blocked'] == 1){
            return $this->respondNotAcceptable(['msg'=>'User is blocked']);
        }

        $order = OrderAttraction::find($id);
        if (!$order) {
            return $this->respondNotFound(['msg' => 'Order does not exist']);
        }

        if ($order->payment_method == "credit_card") {
            return $this->respondNotAcceptable(['msg' => 'You cannot cancel an online order']);
        }
        if ($user->attraction_orders->contains($id)) {
                Attraction::find($order['attraction_id'])->decrement('number_of_going');
                $order->is_canceled = 1;
                $order->save();
                return $this->respondAccepted(['msg' => 'Order has been canceled successfully']);
        }
        return $this->respondAccepted(['msg' => 'Order does not found']);

    }



    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/history/attractions/upcoming",
     *      summary="View User's Upcoming Tickets of Attraction's orders",
     *      tags={"History"},
     *      description="History of Upcoming Tickets of Attraction's orders of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewUpcomingOrderAttraction($lang)
    {

        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg' => 'User is blocked']);
        }


        $orders = OrderAttraction::whereHas('attraction', function ($query) {
            $query->where('date', '>=', Carbon::today());
        })->with('attraction')->with('ticket_order')->with('attraction_ticket')->with('addon_order')->with('attraction_addon')->
            with('order_week_day')->with('order_exceptional_date')->with('attraction.media')->where('user_id', $user->id)->get()->toArray();

        $orders = $this->order_attraction_transformer->transformCollection($orders);

        return $this->respondAccepted($orders);
    }

    /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Get(
     *      path="/{lang}/history/attractions/past",
     *      summary="View User's Past Tickets of Attraction's orders",
     *      tags={"History"},
     *      description="History of Past Tickets of Attraction's orders of the user",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of history en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="Authorization",
     *          description="Token",
     *          type="string",
     *          required=true,
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


    public function viewPastOrderAttraction($lang)
    {
        $user = JWTAuth::toUser(JWTAuth::getToken());
        if($user->is_blocked == 1){
            return $this->respondNotAcceptable(['msg' => 'User is blocked']);
        }
        $orders = OrderAttraction::whereHas('attraction', function ($query) {
            $query->where('date', '<=', Carbon::today());
        })->with('attraction')->with('ticket_order')->with('attraction_ticket')->with('addon_order')->with('attraction_addon')->
        with('order_week_day')->with('order_exceptional_date')->with('attraction.media')->where('user_id', $user->id)
            ->get()->toArray();
        $orders = $this->order_attraction_transformer->transformCollection($orders);

        return $this->respondAccepted($orders);
    }

      /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/filter/category/all",
     *      summary="Filer by categories",
     *      tags={"Search"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of filter en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="category",
     *          description="category: [id-1, id-2]",
     *          type="string",
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


    public function filerCategory($lang, Request $request)
    {
        $events = Event::where('draft', 1)->with('media')->with('categories')->where('end_date', '>=', Carbon::today());
        $venues = Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories');
        $attractions = Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories');

        if($request->input('category')){
            $category =  $request->input('category');

            $events->whereHas('categories',  function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

            $venues->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

            $attractions->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });
        }

        $events= $events->get()->toArray();
        $venues= $venues->get()->toArray();
        $attractions=$attractions->get()->toArray();

        $events=$this->transformer->transformCollection($events);
        $venues=$this->venue_transformer->transformCollection($venues);
        $attractions=$this->attraction_transformer->transformCollection($attractions);
        $result=array_merge($events,$venues,$attractions);
        usort($result, [$this,'sortByOrder']);
        if(!$result) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($result)]);

    }

          /**
     * @param string $lang
     * @return Response
     *
     * @SWG\Post(
     *      path="/{lang}/filter/category",
     *      summary="Filer Venues and Attractions by categories",
     *      tags={"Search"},
     *      description="",
     *      consumes={"application/x-www-form-urlencoded"},
     *       produces={"application/xml", "application/json"},
     *      @SWG\Parameter(
     *          name="lang",
     *          description="language of filter en or ar",
     *          type="string",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="category",
     *          description="category: [id-1, id-2]",
     *          type="string",
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


    public function filerVenuesAndAttractions($lang, Request $request)
    {
        $venues = Venue::where('draft', 1)->with('media')->with('categories')->with('sub_categories');
        $attractions = Attraction::where('draft', 1)->with('media')->with('categories')->with('categories.media')->with('sub_categories');

        if($request->input('category')){
            $category =  $request->input('category');

            $venues->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });

            $attractions->whereHas('categories', function($query) use ($category) {
                $query->whereIn('categories.id', $category);
            });
        }

        $venues= $venues->get()->toArray();
        $attractions=$attractions->get()->toArray();

        $venues=$this->venue_transformer->transformCollection($venues);
        $attractions=$this->attraction_transformer->transformCollection($attractions);
        $result=array_merge($venues,$attractions);
        usort($result, [$this,'sortByOrder']);
        if(!$result) {
            return $this->respondNotFound(['result'=>'No results found']);
        }
        return $this->respondAccepted(['result' => array_reverse($result)]);

    }

}
