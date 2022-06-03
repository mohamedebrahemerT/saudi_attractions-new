<?php namespace App\Transformers;


use Illuminate\Support\Facades\Config;

class OrderAttractionTransformer extends Transformer
{

    public function transform($order)
    {

        return [
            'id'             =>   $order['id'],
            'attraction'     =>   $this->transformAttraction($order['attraction']),
            "tickets"        =>    $this->transformCollection($order['ticket_order'], 'transformAttractionTicket'),
            "addons"        =>    $this->transformCollection($order['addon_order'], 'transformAttractionAddon'),
            "week_days"        =>    $this->transformAttractionTicketOption($order['order_week_day']? $order['order_week_day'][0]:[]),
            "exceptional_dates" =>    $this->transformAttractionTicketDates($order['order_exceptional_date']? $order['order_exceptional_date'][0]:[]),
            'payment_method' =>   Config::get('payment_method.'.$order['payment_method']),
            'order_number'   =>   $order['order_number'],
            'date'           =>   $order['date'],
            'order_status'   =>   Config::get('order_status.'. $order['status'])
        ];
    }

    public function transformAttraction($attractions)
    {
        $attractions_array = [];

        $attractions_array['id'] = $attractions['id'];
        $attractions_array['title'] = isset($attractions['title'])?$attractions['title']:null;
        $attractions_array['image'] = asset($attractions['media']['image']);

        return $attractions_array;

    }

    public function transformAttractionTicket($tickets)
    {
        $tickets_array = [];

        $tickets_array['id'] = $tickets['id'];
        $tickets_array['type'] = isset($tickets['type'])?$tickets['type']:null;
        $tickets_array['number_of_tickets'] = $tickets['pivot']['number_of_tickets'];


        return $tickets_array;

    }

    public function transformAttractionAddon($addons)
    {
        $addons_array = [];

        $addons_array['id'] = $addons['id'];
        $addons_array['name'] = isset($addons['name'])?$addons['name']:null;
        $addons_array['number_of_tickets'] = $addons['pivot']['number_of_tickets'];


        return $addons_array;

    }

    public function transformAttractionTicketOption($options)
    {
        $options_array = [];
        if($options){
            $options_array['id'] = $options['id'];
            $options_array['start_time'] = $options['start_time'];
            $options_array['end_time'] = $options['end_time'];
        }
        return $options_array;

    }

    public function transformAttractionTicketDates($dates)
    {
        $dates_array = [];
        if($dates){
            $dates_array['id'] = $dates['id'];
            $dates_array['start_time'] = $dates['start_time'];
            $dates_array['end_time'] = $dates['end_time'];
        }
        return $dates_array;
    }

}
