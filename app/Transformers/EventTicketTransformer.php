<?php namespace App\Transformers;


class EventTicketTransformer extends Transformer
{

    public function transform($ticket)
    {
        return [
            "id"                   =>    $ticket['id'],
            "type"                 =>    isset($ticket['type'])?$ticket['type']:null,
            "description"          =>    isset($ticket['description'])?$ticket['description']:null,
            "price"                =>    $ticket['price'],
            "number_of_tickets"    =>    $ticket['number_of_tickets'],
            "event"                =>    isset($ticket['event'])?$this->transformEvent($ticket['event']):null,
        ];
    }

    public function transformEvent($events)
    {

        $events_array['id']=$events['id'];
        $events_array['title']=isset($events['title'])?$events['title']:null;

        return $events_array;
    }

}

