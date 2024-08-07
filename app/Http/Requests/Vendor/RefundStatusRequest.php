<?php

namespace App\Http\Requests\Vendor;

use App\Traits\ResponseHandler;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RefundStatusRequest extends FormRequest
{
    use ResponseHandler;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
        public function rules(): array
        {
            return [
                'id' => 'required',
                'refund_status' => 'required|in:pending,approved,rejected,refunded',
                'approved_note' => $this->input('refund_status') == 'approved' ? 'required' : '',
                'rejected_note' => $this->input('refund_status') == 'rejected' ? 'required': '',
            ];
        }
        public function messages(): array
        {
            return [
                'approved_note.required' => translate('The_approved_note_field_is_required'),
                'rejected_note.required' => translate('The_rejected_note_field_is_required'),
            ];
        }
        protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
        {
            throw new HttpResponseException(response()->json(['errors' => $this->errorProcessor($validator)]));
        }
    }
