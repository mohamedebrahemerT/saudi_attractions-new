<?php namespace App\Transformers;


class ContactUsExportTransformer extends Transformer
{

    public function transform($data)
    {

        return [
            'subject'                    =>    $data['subject'],
            'user '                      =>    $data['user']['name'],
            "message"                    =>    $data['message']
        ];
    }

}

