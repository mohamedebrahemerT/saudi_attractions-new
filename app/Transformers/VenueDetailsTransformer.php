<?php namespace App\Transformers;


class VenueDetailsTransformer extends Transformer
{

    public function transform($venue)
    {
        return [
            "id"                                 =>    $venue['id'],
            "title"                              =>    isset($venue['title'])?$venue['title']:null,
            "image"                              =>    asset($venue['media']['image']),
            "gallery"                            =>    isset($venue['gallery'])?$this->transformCollection($venue['gallery'],'transformGallery'):null,
            "categories"                         =>    isset($venue['categories'])?$this->transformCollection($venue['categories'],'transformCategory'):null,
            "sub_categories"                     =>    isset($venue['sub_categories'])?$this->transformCollection($venue['sub_categories'],'transformSubCategory'):null,
            "address"                            =>    isset($venue['address'])?$venue['address']:null,
            "location"                           =>    $venue['location'],
            "lat"                                =>    $venue['lat'],
            "lng"                                =>    $venue['lng'],
            "social_media"                       =>    isset($venue['social_media'])?$this->transformCollection($venue['social_media'],'transformSocialMedia'):null,
            "description"                        =>    isset($venue['description'])?$venue['description']:null,
            "email"                              =>    $venue['email'],
            "website"                            =>    $venue['website'],
            "is_featured"                        =>    $venue['is_featured'],
            "is_sponsored"                       =>    $venue['is_sponsored'],
            "is_brand"                           =>    $venue['is_brand'],
            "week_suggest"                       =>    $venue['week_suggest'],
            "number_of_likes"                    =>    $venue['number_of_likes'],
            "telephone_numbers"                  =>    $venue['telephone_numbers'],
            "venue_opening_hours"                =>    isset($venue['venue_opening_hours'])?$this->transformCollection($venue['venue_opening_hours'],'transformOpeningHours'):null,

        ];
    }


    public function transformGallery($gallery)
    {
        $gallery_array=asset($gallery['image']);

        return $gallery_array;
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

    public function transformOpeningHours($opening_hours)
    {
        $opening_hours_array=[];

        $opening_hours_array['id']=$opening_hours['id'];
        $opening_hours_array['venue_day']=$opening_hours['venue_days']['day'];
        $opening_hours_array['start_time']=date('h:i A', strtotime($opening_hours['start_time']));
        $opening_hours_array['end_time']=date('h:i A', strtotime($opening_hours['end_time']));
        $opening_hours_array['is_closed']=$opening_hours['is_closed'];

        return $opening_hours_array;
    }

}

