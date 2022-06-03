<?php namespace App\Transformers;


class EventDetailsTransformer extends Transformer
{

    public function transform($event)
    {
        return [
            "id"                                 =>    $event['id'],
            "title"                              =>    isset($event['title'])?$event['title']:null,
            "image"                              =>    asset($event['media']['image']),
            "gallery"                            =>    isset($event['gallery'])?$this->transformCollection($event['gallery'],'transformGallery'):null,
            "start_date"                         =>    $event['start_date'],
            "end_date"                           =>    $event['end_date'],
            "categories"                         =>    isset($event['categories'])?$this->transformCollection($event['categories'],'transformCategory'):null,
            "sub_categories"                     =>    isset($event['sub_categories'])?$this->transformCollection($event['sub_categories'],'transformSubCategory'):null,
            "address_url"                        =>    $event['address_url'],
            "address"                            =>    isset($event['address'])?$event['address']:null,
            "event_days"                         =>    isset($event['event_days'])?$this->transformCollection($event['event_days'],'transformEventDays'):null,
            "social_media"                       =>    isset($event['social_media'])?$this->transformCollection($event['social_media'],'transformSocialMedia'):null,
            "event_tags"                         =>    isset($event['tags'])?$this->transformCollection($event['tags'],'transformEventTags'):null,
            "event_tickets"                      =>    isset($event['event_tickets'])?$this->transformCollection($event['event_tickets'],'transformEventTicket'):null,
            "ticket_dates"                       =>    isset($event['ticket_dates'])?$this->transformCollection($event['ticket_dates'],'transformTicketDate'):null,
            "description"                        =>    isset($event['description'])?$event['description']:null,
            "start_price"                        =>    $event['start_price'],
            "end_price"                          =>    $event['end_price'],
            "credit_card"                        =>    $event['credit_card'],
            "cash_on_delivery"                   =>    $event['cash_on_delivery'],
            "pay_later"                          =>    $event['pay_later'],
            "free"                               =>    $event['free'],
            "max_of_pay_later_tickets"           =>    $event['max_of_pay_later_tickets'],
            "max_of_cash_tickets"                =>    $event['max_of_cash_tickets'],
            "max_of_free_tickets"                =>    $event['max_of_free_tickets'],
            "national_id"                        =>    $event['national_id'],
            "is_featured"                        =>    $event['is_featured'],
            "week_suggest"                       =>    $event['week_suggest'],
            "interested"                         =>    $event['number_of_likes'],
            "going"                              =>    $event['number_of_going'],
            "lat"                                =>    $event['lat'],
            "lng"                                =>    $event['lng']
        ];
    }


    public function transformGallery($gallery)
    {
        $gallery_array=asset($gallery['image']);

        return $gallery_array;
    }

    public function transformCategory($category)
    {
        $categories_array =[];

        $categories_array['name']=$category['name'];
        $categories_array['icon']=asset($category['media']['image']);

        return $categories_array;
    }


    public function transformSubCategory($sub_category)
    {
        $sub_categories_array=$sub_category['name'];

        return $sub_categories_array;
    }

    public function transformEventDays($days)
    {
        $days_array=[];

        $days_array['id']=$days['id'];
        $days_array['start_date']= $days['start_date'];
        $days_array['start_time']=date('h:i A', strtotime($days['start_time']));
        $days_array['end_time']=date('h:i A', strtotime($days['end_time']));

        return $days_array;
    }

    public function transformEventTags($tags)
    {
        $tags_array=[];

        $tags_array['id']=$tags['id'];
        $tags_array['name']= isset($tags['name'])?$tags['name']:null;
        $tags_array['icon']=asset($tags['media']['image']);


        return $tags_array;
    }

    public function transformSocialMedia($social_media)
    {

            $social_media_array=[];

            $social_media_array['id']=$social_media['id'];
            $social_media_array['url']=$social_media['pivot']['url'];
            $social_media_array['url_name']=$social_media['pivot']['name'];
            $social_media_array['name']=$social_media['name'];
            $social_media_array['icon']=asset($social_media['media']['image']);
            if($social_media['pivot']['url'] == null){
                return null;
            }
            return $social_media_array;

    }

    public function transformEventTicket($tickets)
    {
        $tickets_array=[];

        $tickets_array['id']=$tickets['id'];
        $tickets_array['type']= isset($tickets['type'])?$tickets['type']:null;
        $tickets_array['description']=isset($tickets['description'])?$tickets['description']:null;
        $tickets_array['price']=$tickets['price'];
        $tickets_array['number_of_tickets']=$tickets['number_of_tickets'];

        return $tickets_array;
    }

    public function transformTicketDate($dates)
    {
        $dates_array=[];

        $dates_array['id']=$dates['id'];
        $dates_array['date']= $dates['date'];
        $dates_array['time']=date('h:i A', strtotime($dates['time']));

        return $dates_array;
    }
}

