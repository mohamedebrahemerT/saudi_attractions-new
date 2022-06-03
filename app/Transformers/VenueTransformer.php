<?php namespace App\Transformers;



use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\SocialMedia;
class VenueTransformer extends Transformer
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
            "social_media"      =>      isset($venue['social_media'])?$this->transformCollection($venue['social_media'],'transformSocialMedia'):null,
            "lat"                 =>    $venue['lat'],
            "lng"                 =>    $venue['lng'],
            "is_sponsored"        =>    $venue['is_sponsored'],
            "is_featured"         =>    $venue['is_featured'],
            "is_brand"            =>    $venue['is_brand'],
            "number_of_likes"     =>    $venue['number_of_likes'],
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


    public function transformSocialMedia($social_media)
    {
        $social_media_array=[];

        $SocialMedia = SocialMedia::find($social_media['pivot']['social_media_id']);
        $social_media_array['id']=$social_media['id'];
        $social_media_array['url']=$social_media['pivot']['url'];
        $social_media_array['url_name']=$social_media['pivot']['name'];
        $social_media_array['name']=$social_media['name'];
        $social_media_array['icon']=$SocialMedia['media']['image'];
        if($social_media['pivot']['url'] == null){
            return null;
        }
        return $social_media_array;
    }

    public function transformSubCategory($sub_category)
    {
        $sub_categories_array=$sub_category['name'];

        return $sub_categories_array;
    }

}

