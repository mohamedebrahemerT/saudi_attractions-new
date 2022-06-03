<?php namespace App\Transformers;


class AttractionDetailsTransformer extends Transformer
{

    public function transform($attraction)
    {
        return [
            "id"                                 =>    $attraction['id'],
            "title"                              =>    isset($attraction['title'])?$attraction['title']:null,
            "image"                              =>    asset($attraction['media']['image']),
            "gallery"                            =>    isset($attraction['gallery'])?$this->transformCollection($attraction['gallery'],'transformGallery'):null,
            "categories"                         =>    isset($attraction['categories'])?$this->transformCollection($attraction['categories'],'transformCategory'):null,
            "sub_categories"                     =>    isset($attraction['sub_categories'])?$this->transformCollection($attraction['sub_categories'],'transformSubCategory'):null,
            "address"                            =>    isset($attraction['address'])?$attraction['address']:null,
            "address_url"                        =>    $attraction['address_url'],
            "lat"                                =>    $attraction['lat'],
            "lng"                                =>    $attraction['lng'],
            "attraction_tags"                    =>    isset($attraction['tags'])?$this->transformCollection($attraction['tags'],'transformAttractionTags'):null,
            "social_media"                       =>    isset($attraction['social_media'])?$this->transformCollection($attraction['social_media'],'transformSocialMedia'):null,
            "attraction_tickets"                 =>    isset($attraction['attraction_tickets'])?$this->transformCollection($attraction['attraction_tickets'],'transformAttractionTicket'):null,
            "attraction_addons"                  =>    isset($attraction['attraction_addons'])?$this->transformCollection($attraction['attraction_addons'],'transformAttractionAddon'):null,
            "attraction_exceptional_dates"       =>    isset($attraction['attraction_exceptional_dates'])?$this->transformCollection($attraction['attraction_exceptional_dates'],'transformAttractionExceptionalDate'):null,
            "description"                        =>    isset($attraction['description'])?$attraction['description']:null,
            "is_featured"                        =>    $attraction['is_featured'],
            "is_sponsored"                       =>    $attraction['is_sponsored'],
            "week_suggest"                       =>    $attraction['week_suggest'],
            "number_of_days"                     =>    $attraction['number_of_days'],
            "cash_on_delivery"                   =>    $attraction['cash_on_delivery'],
            "credit_card"                        =>    $attraction['credit_card'],
            "pay_later"                          =>    $attraction['pay_later'],
            "free"                               =>    $attraction['free'],
            "max_of_pay_later_tickets"           =>    $attraction['max_of_pay_later_tickets'],
            "max_of_cash_tickets"                =>    $attraction['max_of_cash_tickets'],
            "max_of_free_tickets"                =>    $attraction['max_of_free_tickets'],
            "number_of_likes"                    =>    $attraction['number_of_likes'],
            "number_of_going"                    =>    $attraction['number_of_going'],
            "contact_numbers"                    =>    $attraction['contact_numbers'],
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

    public function transformAttractionTags($tags)
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
        $social_media_array['icon']=$social_media['media']['image'];
        if($social_media['pivot']['url'] == null){
            return null;
        }

        return $social_media_array;
    }

    public function transformAttractionTicket($types)
    {
        $types_array=isset($types['type'])?$types['type']:null;

        return $types_array;
    }

    public function transformAttractionAddon($addons)
    {
        $addons_array=isset($addons['name'])?$addons['name']:null;

        return $addons_array;
    }


    public function transformAttractionExceptionalDate($exceptional)
    {
        $exceptional_array=[];
        $tickets_array=[];
        $addons_array=[];

        foreach ($exceptional['types'] as $key=> $except) {
            $tickets_array[$key]['id'] = $except['id'];
            $tickets_array[$key]['type'] = isset($except['type'])?$except['type']:null;
        }

        foreach ($exceptional['addons'] as $key=> $except) {
            $addons_array[$key]['id'] = $except['id'];
            $addons_array[$key]['name'] = isset($except['name'])?$except['name']:null;
        }

        $exceptional_array['id']=$exceptional['id'];
        $exceptional_array['date']= $exceptional['date'];
        $exceptional_array['start_time']= $exceptional['start_time'];
        $exceptional_array['end_time']= $exceptional['end_time'];
        $exceptional_array['types']=$tickets_array;
        $exceptional_array['addons']=$addons_array;
        $exceptional_array['type'] = 'exceptional_date';

        return $exceptional_array;
    }

}

