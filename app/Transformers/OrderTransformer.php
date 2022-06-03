<?php namespace App\Transformers;


use Illuminate\Support\Facades\Config;

class OrderTransformer extends Transformer
{

    public function transform($order)
    {

        return [
            'id' => $order['id'],
            "event"          =>    isset($order['event'])?$this->transformEvent($order['event']):null,
            "event_ticket"          =>    isset($order['event_ticket'])?$this->transformEventTicket($order['event_ticket']):null,
            "ticket_date"          =>    isset($order['ticket_date'])?$this->transformTicketDate($order['ticket_date']):null,
            'number_of_tickets' => $order['number_of_tickets'],
            'payment_method' => Config::get('payment_method.'.$order['payment_method']),
            'order_number' => $order['order_number'],
            'order_status' =>Config::get('order_status.'. $order['status'])
        ];
    }

    public function transformEvent($events)
    {
        $events_array = [];

        $events_array['id'] = $events['id'];
        $events_array['title'] = isset($events['title'])?$events['title']:null;
        $events_array['image'] = asset($events['media']['image']);
        $events_array['start_date'] = $events['start_date'];
        $events_array['end_date'] = $events['end_date'];

        return $events_array;

    }


    public function transformTicketDate($dates)
    {
        $dates_array = [];

        $dates_array['id'] = $dates['id'];
        $dates_array['date'] = $dates['date'];
        $dates_array['time'] = date('h:i A', strtotime($dates['time']));

        return $dates_array;

    }


    public function transformEventTicket($tickets)
    {
        $tickets_array = [];

        $tickets_array['id'] = $tickets['id'];
        $tickets_array['type'] = isset($tickets['type'])?$tickets['type']:null;
        $tickets_array['price'] = $tickets['price'];

        return $tickets_array;

    }
}

