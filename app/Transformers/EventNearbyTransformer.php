<?php namespace App\Transformers;


use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class EventNearbyTransformer extends Transformer
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
            "categories"          =>    isset($event['categories'])?$this->transformCollection($event['categories'],'transformCategory'):null,
            "sub_categories"      =>    isset($event['sub_categories'])?$this->transformCollection($event['sub_categories'],'transformSubCategory'):null,
            "address"             =>    isset($event['address'])?$event['address']:null,
            "lat"                 =>    $event['lat'],
            "lng"                 =>    $event['lng'],
            "is_liked"            =>    $is_liked,
            "created_at"          =>    strtotime($event['created_at']),
            "type"                =>    'event',


        ];
    }


    public function transformCategory($category)
    {

        $categories_array=$category['name'];

        return $categories_array;
    }


    public function transformSubCategory($sub_category)
    {
        $sub_categories_array=$sub_category['name'];

        return $sub_categories_array;
    }

}

