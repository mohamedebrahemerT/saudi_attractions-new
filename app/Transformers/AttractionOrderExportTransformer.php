<?php namespace App\Transformers;


class AttractionOrderExportTransformer extends Transformer
{

    public function transform($data)
    {

        return [
            'name'                       =>    $data['name'],
            'username'                   =>    $data['user']['name'],
            'address'                    =>    $data['user']['address'],
            'email'                      =>    $data['email'],
            'mobile_number'              =>    $data['mobile_number'],
            'attraction'                 =>    $data['attraction']['title'],
            'date'                       =>    $data['date'],
            'total'                      =>    $data['total'],
            'payment_method'             =>    $data['payment_method'],
            'status'                     =>    $data['status']

        ];
    }

}
