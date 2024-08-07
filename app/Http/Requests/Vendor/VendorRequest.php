<?php

namespace App\Http\Requests\Vendor;

use App\Traits\ResponseHandler;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VendorRequest extends FormRequest
{
    use ResponseHandler;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize():bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules():array
    {
        return [
            'f_name' => 'required',
            'l_name' => 'required',
            'image' => 'mimes:jpg,jpeg,png,webp,gif,bmp,tif,tiff',
            'phone'  => 'required|max:20|min:4|unique:sellers,phone,'.$this->id,
        ];
    }

    /**
     * @return array
     */
    public function messages():array
    {
        return [
            'f_name.required' => translate('first_name_is_required').'!',
            'l_name.required' =>translate('last_name_is_required').'!',
            'image.mimes' => translate('The_image_type_must_be').'.jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff,.webp',
            'phone.required' =>translate('phone_number_is_required').'!',
            'phone.max' => translate('please_ensure_your_phone_number_is_valid_and_does_not_exceed_20_characters'),
            'phone.min' => translate('phone_number_with_a_minimum_length_requirement_of_4_characters'),
        ];
    }
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
    }
}
