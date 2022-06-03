<?php

function uploadFile($file,$folder)
{
    $fileName="";
    if ($file && $file->isValid()) {
        $extension = $file->getClientOriginalExtension();
        $fileNameWithoutExtension=time().rand(11111,99999);
        $filePath=$fileNameWithoutExtension.'.'.$extension;
        $fileName ='uploads/'.$folder.'/'.$filePath;
        $file->move(public_path().'/uploads/'.$folder,$fileName);
        $news_input['image']=$fileName;

        }
    return $fileName;
}

function pushNotification($tokens,$title,$message,$data)
{
    $header = array(
        'Content-Type: application/json',
        "Authorization: Basic Zjg1NjExYTYtY2Y2MS00ODI3LWIwYmItYTdlZmVmZDg4Nzc1"
    );
    $notification_data = $data;

    $parameters['app_id'] = "6e15989d-8f46-4844-a331-e57b446ea360";
    if(!empty($tokens))
    {
        $parameters['include_player_ids'] = $tokens;
    }
    else
    {
        $parameters['included_segments'] = "Active Users";
    }
    $parameters['contents'] = $message;
    $parameters['headings'] = $title;
    $parameters['data'] = [];
    $parameters['data'] = array_merge($parameters['data'], $notification_data);
    $parameters["delivery_time_of_day"] = 30 * 60 * 8;
    $parameters = json_encode($parameters);
    $url = 'https://onesignal.com/api/v1/notifications';
    array_push($header, 'cache-control: no-cache');
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => $header,
        CURLOPT_POSTFIELDS => $parameters,
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    return $response;
}


function storeNotification($english_title, $english_description, $arabic_title, $arabic_description, $type)
{
    $input = [
        'english_title'=> $english_title,
        'english_description'=> $english_description,
        'arabic_title' => $arabic_title,
        'arabic_description' => $arabic_description,
        'type' => $type
    ];

    return \App\Models\Notification::create($input);

}

function strbool($value)
{
    return $value ? 'true' : 'false';
}
