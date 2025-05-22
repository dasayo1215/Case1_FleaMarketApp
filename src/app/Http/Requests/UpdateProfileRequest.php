<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;

class UpdateProfileRequest extends FormRequest
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
        $addressRules = (new AddressRequest())->rules();
        $profileRules = (new ProfileRequest())->rules();

        return array_merge($addressRules, $profileRules);
    }

    public function messages()
    {
        $addressMessages = (new AddressRequest())->messages();
        $profileMessages = (new ProfileRequest())->messages();

        return array_merge($addressMessages, $profileMessages);
    }
}
