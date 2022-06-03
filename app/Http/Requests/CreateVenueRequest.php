<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Venue;

class CreateVenueRequest extends FormRequest
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
        return Venue::$rules;
    }

    public function messages()
    {
        return [
            'gallery.*.mimes:jpeg,jpg,png,svg,mp4,mov,ogg,qt' => 'The gallery must be a file of type: jpeg,jpg,png,svg,mp4,mov,ogg,qt.',
            'venue_opening_hours.*.start_time.required_with' => 'The start time of opening hours field is required when end time is present.',
            'telephone_numbers.*.required' => 'The telephone number field is required.',
            'telephone_numbers.*.digits_between:14,22' => 'The telephone number field must be between 14 and 22 digits.',
        ];
    }
}
