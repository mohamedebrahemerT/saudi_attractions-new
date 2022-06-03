<?php

namespace App\Http\Controllers;

use App\EventTicket;
use App\Models\Attraction;
use App\Models\Event;
use App\Order;
use App\AttractionTicketNumber;
use App\UserAttractionTicket;
use App\OrderAttraction;
use App\Transformers\AttractionOrderExportTransformer;
use App\Transformers\OrderExportTransformer;
use App\UserTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Facades\Excel;

class ListAttractionOrderController extends Controller
{

    protected $transformer;


    public function __construct(AttractionOrderExportTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index()
    {
        $orders = OrderAttraction::paginate(10);
        $attraction = Attraction::get();
        $payment = ['cash_on_delivery' => 'cash_on_delivery', 'pay_later' => 'pay_later', 'credit_card' => 'credit_card'];
        return view('requests.attraction_orders.index')
            ->with('orders', $orders)->with('attraction', $attraction)->with('payment', $payment);
    }

    public function approve($id)
    {
        $order = OrderAttraction::find($id);
        $order->update(['status' => 1]);
        $attraction = Attraction::where('id', $order->attraction_id)->first();
        $tickets=[];
        $tickets_id = AttractionTicketNumber::all()->where('order_id', $order->id);
        $tickets = UserAttractionTicket::all()->where('order_id', $order->id);

        Mail::send('emails.attraction_approve', [
            'name' => $order->name,
            'attraction_image' => $attraction->media->image,
            'mobile_number' => $order->mobile_number,
            'attraction_name' => $attraction->title,
            'attraction_location' => $attraction->address,
            'payment_method' => $order->payment_method,
            'order_number' => $order->order_number,
            'date' => $order->date,
            'tickets_id' => $tickets_id,
            'tickets' => $tickets,
            'attraction_social' => $attraction->social_media,
            'total' => $order->total


        ], function ($message) use ($order) {

            $message->from('no-reply@saudiattractions.net', 'Saudi Attractions App');

            $message->subject("Confirmation Approved Attraction Order");

            $message->to($order->email);
        });

        Flash::success('Order has been approved successfully.');
        return redirect(route('attraction_orders.index'));
    }

    public function reject($id)
    {
        $order = OrderAttraction::find($id)->update(['status' => 2]);

        Flash::success('Order has been rejected successfully.');

        return redirect(route('attraction_orders.index'));
    }

    public function exportAttractionOrders($type)
    {

        $orders = OrderAttraction::with('user')->with('attraction');
        $attraction = Attraction::get();
        $payment = ['cash_on_delivery' => 'cash_on_delivery', 'pay_later' => 'pay_later', 'credit_card' => 'credit_card'];

        if (!empty($_GET['attraction_id'])) {
            $title = $_GET['attraction_id'];
            $orders = $orders->whereHas('attraction', function ($query) use ($title) {
                $query->where('id', $title);
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

            return Excel::create('Attraction Orders', function ($excel) use ($orders) {

                $excel->sheet('mySheet', function ($sheet) use ($orders) {

                    $sheet->fromArray($orders);

                });

            })->download($type);
        }
        else {
            $orders = $orders->paginate(10);
            return view('requests.attraction_orders.index')
                ->with('orders', $orders)->with('attraction', $attraction)->with('payment', $payment);
        }
    }


    public function approvedOrders()
    {
        $orders = OrderAttraction::where('status', 1)->latest()->paginate(10);

        return view('requests.attraction_orders.approved_orders')
            ->with('orders', $orders);
    }

    public function showApprovedOrder()
    {
        $order = OrderAttraction::with('user')->with('attraction')->where('status', 1);

        if (!empty($_GET['order_number'])) {
            $order = $order->where('order_number', $_GET['order_number']);
        } else {
            Flash::error('Order not found');

            return redirect(route('attraction_orders.approved_orders'));
        }
        $order = $order->first();
        if (!$order) {
            Flash::error('Order not found');

            return redirect(route('attraction_orders.approved_orders'));
        }
        return view('requests.attraction_orders.approved_show')->with('order', $order);
    }

    public function showOrder()
    {
        $order = OrderAttraction::with('user')->with('attraction');

        if (!empty($_GET['order_number'])) {
            $order = $order->where('order_number', $_GET['order_number']);
        }
        else{
            Flash::error('Order not found');

            return redirect(route('attraction_orders.index'));
        }
        $order=$order->first();
        if(! $order){
            Flash::error('Order not found');
            return redirect(route('attraction_orders.index'));
        }
        return view('requests.attraction_orders.approved_show')->with('order', $order);
    }

    public function verifyOrders($id)
    {
        $order = OrderAttraction::find($id)->update(['is_verified' => 1]);

        Flash::success('Order has been verified successfully.');

        return redirect(route('attraction_orders.approved_show'));
    }


}
