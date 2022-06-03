<?php namespace App\Transformers;


class AboutUsTransformer extends Transformer
{

    public function transform($about_us)
    {
        return [
            "id"                 =>    $about_us['id'],
            "paragraph"          =>    isset($about_us['paragraph'])?$about_us['paragraph']:null,
        ];
    }

}

