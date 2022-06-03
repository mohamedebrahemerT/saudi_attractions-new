<?php namespace App\Transformers;


class CategoryTransformer extends Transformer
{

    public function transform($category)
    {
        return [
            "id"                 =>    $category['id'],
            "name"               =>    isset($category['name'])?$category['name']:null,
            "icon"               =>    asset($category['media']['image']),
        ];
    }

}

