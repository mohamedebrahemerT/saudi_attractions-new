<?php namespace App\Transformers;


class UserTransformer extends Transformer
{

    public function transform($user)
    {
        return [
            "id"                     =>    $user['id'],
            "name"                   =>    $user['name'],
            "email"                  =>    $user['email'],
            "mobile_number"          =>    $user['mobile_number'],
            "birth_date"             =>    $user['birth_date'],
            "gender"                 =>    $user['gender'],
            "ja_id"                  =>    $user['ja_id'],
            "country"                =>    $user['country']['name'],
            "city"                   =>    $user['city']['name'],
            "address"                =>    $user['address'],
            "verified"               =>    $user['verified'],
            "profile_image"          =>    ($user['media']['image']) ? asset($user['media']['image']) : "",
        ];
    }


}
