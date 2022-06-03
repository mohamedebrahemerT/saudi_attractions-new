<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\ContactUs;

class CreateContactUsRequest extends FormRequest
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
        return ContactUs::$rules;
    }

    public function messages()
    {
        return [
            'contactMedia.*.image.mimes:jpeg,jpg,png,svg' => 'The image of social platforms must be a file of type: jpeg, jpg, png, svg.',
            'contactMedia.*.url.url' => 'The url of social platforms must be a valid url format',
        ];
    }
}
