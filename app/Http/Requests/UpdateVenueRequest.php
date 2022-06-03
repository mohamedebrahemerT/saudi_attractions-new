<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Venue;

class UpdateVenueRequest extends FormRequest
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
        return [
            'title' => 'required',
            'address' => 'required',
            'email' => 'nullable|email',
            'website' => 'nullable|url',
            'location' => 'required|url',
            'description' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'telephone_numbers' => 'required',
            'telephone_numbers.*' => 'regex:/^[0-9. -]+$/',
            'image'  => 'nullable|mimes:jpeg,jpg,png,svg',
            'gallery.*' => 'mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt',
//            'categories' => 'required',
//            'sub_categories' => 'required',
            'venue_opening_hours.*.start_time' => 'nullable|required_with:venue_opening_hours.*.end_time',
            'social_media_inputs.*.url'=>'nullable|url',
            'social_media_inputs.*.name'=>'nullable',
            'arabic_notification_title' => 'nullable',
            'arabic_notification_description' => 'nullable',
            'english_notification_title' => 'nullable',
            'english_notification_description' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'gallery.*.mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt' => 'The gallery must be a file of type: jpeg,jpg,png,svg,mp4,mov,ogg,qt.',
            'venue_opening_hours.*.start_time.required_with' => 'The start time of opening hours field is required when end time is present.',
            'telephone_numbers.*.digits_between:14,22' => 'The telephone number field must be between 14 and 22 digits.',
        ];
    }
}
