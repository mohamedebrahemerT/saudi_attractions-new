<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Event;

class UpdateEventRequest extends FormRequest
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
            'start_date' => 'required|date',
            'end_date' => 'nullable|after_or_equal:start_date|date',
            'address' => 'required',
            'address_url' => 'required|url',
            'description' => 'required',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'start_price' => 'required_with:end_price|numeric',
            'end_price' => 'nullable|numeric',
            'image'  => 'nullable|mimes:jpeg,jpg,png,svg',
            'max_of_pay_later_tickets' => 'nullable|numeric|min:0',
            'max_of_cash_tickets' => 'nullable|numeric|min:0',
//            'event_tickets.*.type' => 'required',
//            'event_tickets.*.description' => 'required',
//            'event_tickets.*.price' => 'required|numeric',
//            'event_tickets.*.number_of_tickets' => 'required|numeric|min:0',
//            'categories' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png,svg',
//            'tags.*.name' => 'required',
            'tags.*.image' => 'nullable|mimes:jpeg,jpg,png,svg',
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
//            'event_tickets.*.type.required' => 'The type of event tickets field is required.',
//            'event_tickets.*.description.required' => 'The description of event tickets field is required.',
//            'event_tickets.*.price.required' => 'The price of event tickets field is required.',
            'event_tickets.*.price.numeric' => 'The price of event tickets field must be a number.',
//            'event_tickets.*.number_of_tickets.required' => 'The number of tickets of event tickets field is required.',
            'event_tickets.*.number_of_tickets.min:0' => 'The number of tickets of event tickets field must be at least 0.',
            'event_days.*.start_date.required' => 'The start date of event days field is required.',
            'event_days.*.start_date.date' => 'The start date of event days field is not valid.',
            'event_days.*.start_time.required' => 'The start time of event days field is required.',
            'event_days.*.end_time.required' => 'The end time of event days field is required.',
            // 'event_days.*.start_time.before:event_days.*.end_time' => 'The start time of event days field must be a time before end time.',
            'ticket_dates.*.date.required' => 'The date of ticket dates field is required.',
            'ticket_dates.*.date.date' => 'The date of ticket dates field is not valid.',
            'ticket_dates.*.time.required' => 'The time of ticket dates field is required.',
//            'categories.*.required' => 'The name of category field is required.',
//            'sub_categories.*.required' => 'The name of subcategory field is required.',
//            'tags.*.name.required' => 'The name of event tags field is required.',
//            'tags.*.image' => 'The image of event tags field is required.',
            'tags.*.image.mimes:jpeg,jpg,png,svg' => 'The image of event tags must be a file of type: jpeg, jpg, png, svg.',
            'images.*.mimes:jpeg,jpg,png,svg' => 'The images must be a file of type: jpeg, jpg, png, svg.',

        ];
    }
}
