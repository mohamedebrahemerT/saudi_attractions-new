<?php namespace App\Transformers;



use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AttractionNearbyTransformer extends Transformer
{

    public function transform($attraction)
    {
        $is_liked =false;
        try{
            if ($user = JWTAuth::toUser(JWTAuth::getToken())) {
                if ($user->liked_attractions->contains($attraction['id'])) {
                    $is_liked = true;
                }
            }
        }
        catch (JWTException $e) {

        }
        return [
            "id"                  =>    $attraction['id'],
            "title"               =>    isset($attraction['title'])?$attraction['title']:null,
            "image"               =>    asset($attraction['media']['image']),
            "categories"          =>    isset($attraction['categories'])?$this->transformCollection($attraction['categories'],'transformCategory'):null,
            "sub_categories"      =>    isset($attraction['sub_categories'])?$this->transformCollection($attraction['sub_categories'],'transformSubCategory'):null,
            "address"             =>    isset($attraction['address'])?$attraction['address']:null,
            "lat"                 =>    $attraction['lat'],
            "lng"                 =>    $attraction['lng'],
            "is_liked"            =>    $is_liked,
            "created_at"          =>    strtotime($attraction['created_at']),
            "type"                =>   'attraction'
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

