<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attraction;

class CreateAttractionDaysRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return[

            'attraction_week_days.*.types' => 'nullable',
            'attraction_week_days.*.addons' => 'nullable',
            'attraction_week_days.*.start_time' => 'nullable|required_with:attraction_week_days.*.end_time',
            'attraction_exceptional_dates.*.types' => 'nullable',
            'attraction_exceptional_dates.*.addons' => 'nullable',
            'attraction_exceptional_dates.*.start_time' => 'nullable|required_with:attraction_exceptional_dates.*.end_time',
        ];
    }

    public function messages()
    {
        return [
            'attraction_week_days.*.start_time.required_with' => 'The start time of week days options field is required when end time is present.',
            'attraction_exceptional_dates.*.start_time.required_with' => 'The start time of exceptional date field is required when end time is present.',

        ];
    }
}
