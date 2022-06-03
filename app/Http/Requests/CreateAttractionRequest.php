<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attraction;

class CreateAttractionRequest extends FormRequest
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
        return Attraction::$rules;
    }

    public function messages()
    {
        return [
            'attraction_tickets.*.type.required' => 'The type of attraction tickets field is required.',
            'attraction_tickets.*.description.required' => 'The description of attraction tickets field is required.',
            'attraction_tickets.*.price.required' => 'The price of attraction tickets field is required.',
            'attraction_tickets.*.price.numeric' => 'The price of attraction tickets field must be a number.',
            'attraction_tickets.*.number_of_tickets.required' => 'The number of tickets of attraction tickets field is required.',
            'attraction_tickets.*.number_of_tickets.min:0' => 'The number of tickets of event tickets field must be at least 0.',
            'attraction_addons.*.name.required' => 'The name of attraction tickets add-ons field is required.',
            'attraction_addons.*.description.required' => 'The description of attraction tickets add-ons field is required.',
            'attraction_addons.*.price.required' => 'The price of attraction tickets add-ons field is required.',
            'attraction_addons.*.price.numeric' => 'The price of attraction tickets add-ons field must be a number.',
            'attraction_addons.*.number_of_tickets.required' => 'The number of tickets of attraction tickets add-ons field is required.',
            'attraction_addons.*.number_of_tickets.min:0' => 'The number of tickets of event tickets add-ons field must be at least 0.',
            'tags.*.name.required' => 'The name of attraction tags field is required.',
            'tags.*.image.required' => 'The image of attraction tags field is required.',
            'tags.*.image.mimes:jpeg,jpg,png,svg' => 'The image of attraction tags must be a file of type: jpeg, jpg, png, svg.',
            'gallery.*.mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt' => 'The gallery must be a file of type: jpeg, jpg, png, svg, mp4, mov, ogg, qt.',
            'attraction_week_days.*.start_time.required_with' => 'The start time of week options field is required when end time is present.',
            'attraction_week_days.*.start_time.date_format:H:i A' => 'The start time shoud be a valid time 02:00:AM .',
            'attraction_exceptional_dates.*.start_time.required_with' => 'The start time of exceptional dates field is required when end time is present.',
            'attraction_exceptional_dates.*.start_time.date_format:H:i A' => 'The start time shoud be a valid time like 02:00:AM .',
            'contact_numbers.*.required' => 'The contact numbers field is required.',
            // 'contact_numbers.*.numeric' => 'The contact numbers must be a number.',
            // 'contact_numbers.*.digits_between:14,22' => 'The contact numbers field must be between 14 and 22 digits.',

        ];
    }
}
