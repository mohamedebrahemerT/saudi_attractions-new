<?php namespace App\Transformers;


class CountryTransformer extends Transformer
{

    public function transform($country)
    {
        return [
            "id"                 =>    $country['id'],
            "name"               =>    isset($country['name'])?$country['name']:null,
            "cities"             =>    isset($country['cities'])?$this->transformCollection($country['cities'],'transformCity'):null,
        ];
    }

    public function transformCity($city)
    {
        $cities_array=isset($city['name'])?$city['name']:null;

        return $cities_array;
    }

}

