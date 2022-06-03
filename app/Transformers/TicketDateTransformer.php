<?php namespace App\Transformers;


class TicketDateTransformer extends Transformer
{

    public function transform($ticket_dates)
    {
        return [
            "id"                   =>    $ticket_dates['id'],
            "date"                 =>    $ticket_dates['date'],
            "time"                 =>    date('h:i A', strtotime($ticket_dates['time'])),
            "event"                =>    isset($ticket_dates['event'])?$this->transformEvent($ticket_dates['event']):null,
        ];
    }

    public function transformEvent($events)
    {

        $events_array['id']=$events['id'];
        $events_array['title']=isset($events['title'])?$events['title']:null;

        return $events_array;
    }

}

