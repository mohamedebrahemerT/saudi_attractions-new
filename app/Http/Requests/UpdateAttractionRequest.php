<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Attraction;

class UpdateAttractionRequest extends FormRequest
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
            'title' => 'required',
            'address' => 'required',
            'address_url' => 'required|url',
            'description' => 'required',
            'lat' => '|numeric',
            'lng' => '|numeric',
            'number_of_days' => '|numeric|min:0',
            'max_of_pay_later_tickets' => 'nullable|numeric|min:0',
            'max_of_cash_tickets' => 'nullable|numeric|min:0',
            'image'  => 'nullable|mimes:jpeg,jpg,png,svg',
            'attraction_tickets.*.type' => 'required',
            'attraction_tickets.*.description' => 'required',
            'attraction_tickets.*.price' => 'required|numeric|min:0',
            'attraction_tickets.*.number_of_tickets' => 'required|numeric|min:0',
            'attraction_addons.*.name' => 'required',
            'attraction_addons.*.description' => 'required',
            'attraction_addons.*.price' => 'required|numeric|min:0',
            'attraction_addons.*.number_of_tickets' => 'required|numeric|min:0',    
            'contact_numbers' => 'required',
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
            'arabic_notification_title' => 'nullable',
            'arabic_notification_description' => 'nullable',
            'english_notification_title' => 'nullable',
            'english_notification_description' => 'nullable',
            'max_of_free_tickets' => 'nullable|numeric|min:0',
        ];
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
            'tags.*.image.mimes:jpeg,jpg,png,svg' => 'The image of attraction tags must be a file of type: jpeg, jpg, png, svg.',
            'gallery.*.mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt' => 'The gallery must be a file of type: jpeg, jpg, png, svg, mp4, mov, ogg, qt.',
            // 'attraction_week_days.*.start_time.before' => 'The start time of week options field must be a time before end time.',
            'attraction_week_days.*.start_time.required_with' => 'The start time of week options field is required when end time is present.',
            'attraction_week_days.*.start_time.date_format:H:i A' => 'The start time shoud be a valid time 02:00:AM .',
            // 'attraction_exceptional_dates.*.start_time.before' => 'The start time of exceptional dates field must be a time before end time.',
            'attraction_exceptional_dates.*.start_time.required_with' => 'The start time of exceptional dates field is required when end time is present.',
            'attraction_exceptional_dates.*.start_time.date_format:H:i A' => 'The start time shoud be a valid time like 02:00:AM .',
            'contact_numbers.*.required' => 'The contact numbers field is required.',
            // 'contact_numbers.*.numeric' => 'The contact numbers must be a number.',
        ];
    }
}
