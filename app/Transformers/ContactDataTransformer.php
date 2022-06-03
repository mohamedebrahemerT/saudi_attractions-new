<?php namespace App\Transformers;


class ContactDataTransformer extends Transformer
{

    public function transform($contactData)
    {
        return [
            "id"                 =>    $contactData['id'],
            "address"            =>    isset($contactData['address'])?$contactData['address']:null,
            "email"              =>    isset($contactData['email'])?$contactData['email']:null,
            "telephone"          =>    isset($contactData['telephone'])?$contactData['telephone']:null,
            "website"            =>    isset($contactData['website'])?$contactData['website']:null,
            "social_platforms"   =>    isset($contactData['contact_media'])?$this->transformCollection($contactData['contact_media'],'transformSocial'):null,
        ];
    }

    public function transformSocial($social)
    {
        $social_array=[];

        $social_array['id']=$social['id'];
        $social_array['name']=isset($social['name'])?$social['name']:null;
        $social_array['url']=isset($social['url'])?$social['url']:null;
        $social_array['icon']=asset($social['media']['image']);

        return $social_array;
    }



}

