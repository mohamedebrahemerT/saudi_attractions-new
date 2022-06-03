<?php namespace App\Transformers;


use Illuminate\Support\Facades\Config;

class NotificationTransformer extends Transformer
{

    public function transform($notification)
    {

        return [
            "id"                           =>    $notification['id'],
            "english_title"                =>    $notification['english_title'],
            "english_description"          =>    $notification['english_description'],
            "arabic_title"                 =>    $notification['arabic_title'],
            "arabic_description"           =>    $notification['arabic_description'],
            "url"                          =>    Config::get('notificationsScreens.'.$notification['url']),
        ];
    }

}
