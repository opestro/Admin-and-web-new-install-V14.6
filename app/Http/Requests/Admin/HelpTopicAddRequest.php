<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HelpTopicAddRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question' => 'required',
            'answer' => 'required',
            'ranking' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'question.required' => translate('the_question_field_is_required'),
            'answer.required' => translate('the_answer_field_is_required'),
            'ranking.required' => translate('the_ranking_field_is_required'),
        ];
    }

}
