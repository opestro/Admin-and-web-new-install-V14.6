<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class S3CredentialAddOrUpdateRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            's3_key' => 'required',
            's3_secret' => 'required',
            's3_region' => 'required',
            's3_bucket' => 'required',
            's3_url' => 'required',
            's3_endpoint' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            's3_key.required' => translate('The_key_field_is_required'),
            's3_secret.required' => translate('The_secret_field_is_required'),
            's3_region.required' => translate('The_region_field_is_required'),
            's3_bucket.required' => translate('The_bucket_field_is_required'),
            's3_url.required' => translate('The_url_field_is_required'),
            's3_endpoint.required' => translate('The_endpoint_field_is_required'),
        ];;
    }

}
