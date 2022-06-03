<?php namespace App\Transformers;



use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class VenueNearbyTransformer extends Transformer
{

    public function transform($venue)
    {
        $is_liked =false;
        try{
            if ($user = JWTAuth::toUser(JWTAuth::getToken())) {
                if ($user->liked_venues->contains($venue['id'])) {
                    $is_liked = true;
                }
            }
        }
        catch (JWTException $e) {

        }
        return [
            "id"                  =>    $venue['id'],
            "title"               =>    isset($venue['title'])?$venue['title']:null,
            "image"               =>    asset($venue['media']['image']),
            "categories"          =>    isset($venue['categories'])?$this->transformCollection($venue['categories'],'transformCategory'):null,
            "sub_categories"      =>    isset($venue['sub_categories'])?$this->transformCollection($venue['sub_categories'],'transformSubCategory'):null,
            "address"             =>    isset($venue['address'])?$venue['address']:null,
            "lat"                 =>    $venue['lat'],
            "lng"                 =>    $venue['lng'],
            "is_liked"            =>    $is_liked,
            "created_at"          =>    strtotime($venue['created_at']),
            "type"                =>    'venue'
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

