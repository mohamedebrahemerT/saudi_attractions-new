<?php

namespace App\Http\Controllers;

use App\EventTicket;
use App\Models\Event;
use App\Order;
use App\Transformers\OrderExportTransformer;
use App\UserTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ListOrdersController extends Controller
{

    protected $transformer;


    public function __construct(OrderExportTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index()
    {
        $orders = Order::latest()->paginate(50);
        $event = Event::get();
        $payment = ['cash_on_delivery' => 'cash_on_delivery', 'pay_later' => 'pay_later', 'credit_card' => 'credit_card'];
        return view('requests.orders.index')
            ->with('orders', $orders)->with('event', $event)->with('payment', $payment);
    }

    public function approve($id)
    {
        $order = Order::find($id);
        $order->update(['status' => 1]);
        $event = Event::where('id', $order->event_id)->first();
        $tickets_id = UserTicket::all()->where('order_id', $id);

        Mail::send('emails.approve', [
            'name' => $order->name,
            'event_image' => $event->media->image,
            'mobile_number' => $order->mobile_number,
            'event_name' => $event->title,
            'event_date' => $order->ticket_date->date,
            'event_time' => $order->ticket_date->time,
            'event_location' => $order->event->address,
            'payment_method' => $order->payment_method,
            'order_number' => $order->order_number,
            'number_of_tickets' => $order->number_of_tickets,
            'tickets_id' => $tickets_id,
            'total' => $order->total,
            'event_social' => $event->social_media,
            'tickets_id' => $tickets_id,

        ], function ($message) use ($order) {

            $message->from('no-reply@saudiattractions.net', 'Saudi Attractions App');

            $message->subject("Confirmation Approved Order");

            $message->to($order->email);
        });

        Flash::success('Order has been approved successfully.');
        return redirect(route('orders.index'));
    }

    public function reject($id)
    {
        $order = Order::find($id)->update(['status' => 2]);

        Flash::success('Order has been rejected successfully.');

        return redirect(route('orders.index'));
    }

    public function exportOrders($type)
    {

        $orders = Order::with('user')->with('event')->with('event_ticket')->with('ticket_date');
        $event = Event::get();
        $payment = ['cash_on_delivery' => 'cash_on_delivery', 'pay_later' => 'pay_later', 'credit_card' => 'credit_card'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];

        if (!empty($_GET['event_id'])) {
            $title = $_GET['event_id'];
            $orders = $orders->whereHas('event', function ($query) use ($title) {
                $query->where('id', $title);
            });
        }
        if (!empty($_GET['start_date'])) {
            $orders = $orders->whereHas('event', function ($query) use ($end_date, $start_date) {
                $query->where('start_date', '>=', $start_date)->where('end_date', '<=', $end_date);
            });
        }

        if (!empty($_GET['payment_method'])) {
            $orders = $orders->where('payment_method', $_GET['payment_method']);
        }

        if (!empty($_GET['status'])) {
            $orders = $orders->where('status', $_GET['status']);
        }

        if(isset($_GET['export']) == 'export'){
            $orders = $orders->get()->toArray();
            $orders = $this->transformer->transformCollection($orders);

            return Excel::create('Orders', function ($excel) use ($orders) {

                $excel->sheet('mySheet', function ($sheet) use ($orders) {

                    $sheet->fromArray($orders);

                });

            })->download($type);
        }
        else {
            $orders = $orders->paginate(10);
            return view('requests.orders.index')
                ->with('orders', $orders)->with('event', $event)->with('payment', $payment);
        }
    }


    public function approvedOrders()
    {
        $orders = Order::where('status', 1)->latest()->paginate(10);

        return view('requests.orders.approved_orders')
            ->with('orders', $orders);
    }

    public function showApprovedOrder()
    {
        $order = Order::with('user')->with('event')->with('event_ticket')->with('ticket_date')->where('status', 1);

        if (!empty($_GET['order_number'])) {
            $order = $order->where('order_number', $_GET['order_number']);
        } else {
            Flash::error('Order not found');

            return redirect(route('orders.approved_orders'));
        }
        $order = $order->first();
        if (!$order) {
            Flash::error('Order not found');

            return redirect(route('orders.approved_orders'));
        }
        return view('requests.orders.approved_show')->with('order', $order);
    }

    public function showOrder()
    {
        $order = Order::with('user')->with('event')->with('event_ticket')->with('ticket_date');

        if (!empty($_GET['order_number'])) {
            $order = $order->where('order_number', $_GET['order_number']);
        }
        else{
            Flash::error('Order not found');

            return redirect(route('orders.index'));
        }
        $order=$order->first();
        if(! $order){
            Flash::error('Order not found');
            return redirect(route('orders.index'));
        }
        return view('requests.orders.approved_show')->with('order', $order);
    }

    public function verifyOrders($id)
    {
        $order = Order::find($id)->update(['is_verified' => 1]);

        Flash::success('Order has been verified successfully.');

        return redirect(route('orders.approved_show'));
    }


}
