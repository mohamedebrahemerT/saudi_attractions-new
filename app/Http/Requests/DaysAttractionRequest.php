<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attraction;

class DaysAttractionRequest extends FormRequest
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
            'title' => 'nullable',
            'address' => 'nullable',
            'address_url' => 'nullable|url',
            'description' => 'nullable',
            'lat' => '|numeric',
            'lng' => '|numeric',
            'max_of_pay_later_tickets' => 'nullable|numeric|min:0',
            'max_of_cash_tickets' => 'nullable|numeric|min:0',
            'image'  => 'nullable|mimes:jpeg,jpg,png,svg',
            'attraction_ticket_types.*.type' => 'required',
            'attraction_ticket_types.*.description' => 'required',
            'attraction_ticket_types.*.price' => 'required|numeric|min:0',
            'attraction_ticket_types.*.number_of_tickets' => 'required|numeric|min:0',
            'contact_numbers' => 'nullable',
            // 'contact_numbers.*' => 'regex:/^[0-9. -]+$/',
            'gallery'  => 'nullable',
            'gallery.*' =>'mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt',
            'attraction_week_days.*.start_time' => 'nullable|required_with:attraction_week_days.*.end_time',
            'attraction_exceptional_dates.*.start_time' => 'nullable|required_with:attraction_exceptional_dates.*.end_time',    
            'categories'  => '',
            'tags.*.name'  => 'required',
            'tags.*.image'  => 'nullable|mimes:jpeg,jpg,png,svg',
            'social_media_inputs.*.url'=>'nullable|url',
            'social_media_inputs.*.name'=>'nullable',
            'notification_title' => 'nullable',
            'notification_description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'attraction_ticket_types.*.type.required' => 'The type of attraction tickets field is required.',
            'attraction_ticket_types.*.description.required' => 'The description of attraction tickets field is required.',
            'attraction_ticket_types.*.price.required' => 'The price of attraction tickets field is required.',
            'attraction_ticket_types.*.price.numeric' => 'The price of attraction tickets field must be a number.',
            'attraction_ticket_types.*.number_of_tickets.required' => 'The number of tickets of attraction tickets field is required.',
            'attraction_ticket_types.*.number_of_tickets.min:0' => 'The number of tickets of event tickets field must be at least 0.',
            'tags.*.name.required' => 'The name of attraction tags field is required.',
            'tags.*.image.mimes:jpeg,jpg,png,svg' => 'The image of attraction tags must be a file of type: jpeg, jpg, png, svg.',
            'gallery.*.mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt' => 'The gallery must be a file of type: jpeg, jpg, png, svg, mp4, mov, ogg, qt.',
            'attraction_week_days.*.start_time.before' => 'The start time of week options field must be a time before end time.',
            'attraction_week_days.*.start_time.required_with' => 'The start time of week options field is required when end time is present.',
            'attraction_week_days.*.start_time.date_format:H:i A' => 'The start time shoud be a valid time 02:00:AM .',
            'attraction_exceptional_dates.*.start_time.before' => 'The start time of exceptional dates field must be a time before end time.',
            'attraction_exceptional_dates.*.start_time.required_with' => 'The start time of exceptional dates field is required when end time is present.',
            'attraction_exceptional_dates.*.start_time.date_format:H:i A' => 'The start time shoud be a valid time like 02:00:AM .',
            'contact_numbers.*.required' => 'The contact numbers field is required.',
            'contact_numbers.*.numeric' => 'The contact numbers must be a number.',
        ];
    }
}
