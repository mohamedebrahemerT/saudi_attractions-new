<?php namespace App\Transformers;


class AttractionDaysTransformer extends Transformer
{

    public function transform($days)
    {
        return [
            "id"                          =>    $days['id'],
            "day"                         =>    $days['day'],
            "attraction_week_days"        =>    isset($days['attraction_week_days'])?$this->transformAttractionDay($days['attraction_week_days']):null,
        ];
    }

    public function transformAttractionDay($times)
    {
        $time_array=[];
        $type_array=[];
        $addon_array=[];
        $types_array=[];
        $addons_array=[];
        $week_days=[];
        foreach ($times as $key=> $time) {
            $types_array=[];
            $addons_array=[];
            $time_array['is_closed'] = $time['is_closed'];
            $time_array['start_time'] = $time['start_time'];
            $time_array['end_time'] = $time['end_time'];
            foreach ($time['types'] as $key=> $single_time) {
                $type_array['id'] = $single_time['id'];
                $type_array['type'] = isset($single_time['type'])?$single_time['type']:null;
                $types_array[]=$type_array;
            }

            $time_array['types'] = $types_array;


                foreach ($time['addons'] as $key => $single_time) {
                    $addon_array['id'] = $single_time['id'];
                    $addon_array['name'] = isset($single_time['name'])?$single_time['name']:null;
                    $addons_array[] = $addon_array;
                }
                $time_array['addons'] = $addons_array;

            $week_days[]=$time_array;
        }

        return $week_days;
    }

}

