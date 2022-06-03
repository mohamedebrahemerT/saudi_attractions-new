<?php namespace App\Transformers;


class OrderExportTransformer extends Transformer
{

    public function transform($data)
    {

        return [
            'name'                       =>    $data['name'],
            'username'                   =>    $data['user']['name'],
            'address'                    =>    $data['user']['address'],
            'email'                      =>    $data['email'],
            'mobile_number'              =>    $data['mobile_number'],
            'event'                      =>    $data['event']['title'],
            'number_of_tickets'          =>    $data['number_of_tickets'],
            'event_ticket_type'          =>    $data['event_ticket']['type'],
            'ticket_date'                =>    $data['ticket_date']['date'],
            'ticket_time'                =>    $data['ticket_date']['time'],
            'total'                      =>    $data['total'],
            'payment_method'             =>    $data['payment_method'],
            'status'                     =>    $data['status']

        ];
    }

}
