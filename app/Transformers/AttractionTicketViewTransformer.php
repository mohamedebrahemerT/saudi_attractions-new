<?php namespace App\Transformers;


class AttractionTicketViewTransformer extends Transformer
{

    public function transform($tickets)
    {

        return [
            "id"                 =>    $tickets['id'],
            "start_time"         =>    $tickets['start_time'],
            "end_time"           =>    $tickets['end_time'],
            "types"              =>    isset($tickets['types'])?$this->transformCollection($tickets['types'],'transformType'):null,
            "addons"             =>    isset($tickets['addons'])?$this->transformCollection($tickets['addons'],'transformAddon'):null,
        ];
    }

    public function transformType($types)
    {

        $types_array['id']=$types['id'];
        $types_array['type']=isset($types['type'])?$types['type']:null;
        $types_array['description']=isset($types['description'])?$types['description']:null;
        $types_array['price']=$types['price'];
        $types_array['number_of_tickets']=$types['number_of_tickets'];

        return $types_array;
    }

    public function transformAddon($addons)
    {

        $addons_array['id']=$addons['id'];
        $addons_array['name']=isset($addons['name'])?$addons['name']:null;
        $addons_array['description']=isset($addons['description'])?$addons['description']:null;
        $addons_array['price']=$addons['price'];
        $addons_array['number_of_tickets']=$addons['number_of_tickets'];

        return $addons_array;
    }

}

