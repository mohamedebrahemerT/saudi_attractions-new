<?php namespace App\Transformers;


use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EventTransformer extends Transformer
{
    public function transform($event)
    {
        $is_liked =false;
        try{
            if ($user = JWTAuth::toUser(JWTAuth::getToken())) {
                if ($user->liked_events->contains($event['id'])) {
                    $is_liked = true;
                }
            }
        }

        catch (JWTException $e) {
        }
        return [
            "id"                  =>    $event['id'],
            "title"               =>    isset($event['title'])?$event['title']:null,
            "image"               =>    asset($event['media']['image']),
            "start_date"          =>    $event['start_date'],
            "end_date"            =>    $event['end_date'],
            "address"             =>    isset($event['address'])?$event['address']:null,
            "lat"                 =>    $event['lat'],
            "lng"                 =>    $event['lng'],
            "interested"          =>    $event['number_of_likes'],
            "going"               =>    $event['number_of_going'],
            "is_liked"            =>    $is_liked,
            "created_at"          =>    strtotime($event['created_at']),
            "type"                =>    'event',


        ];
    }

}

