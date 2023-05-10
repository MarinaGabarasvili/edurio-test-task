<?php

namespace App\Http\Requests;

use App\Models\AnswerOption;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveAnswersRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'answers' => 'required|array',

            'answers.*.question_id' => [
                'required',
                Rule::exists(Question::class, 'id')
            ],
            'answers.*.answer_option_id' => [
                'nullable',
                Rule::exists(AnswerOption::class, 'id'),
            ],
            'answers.*.user_id' => [
                'required',
                Rule::exists(User::class, 'id')
            ]
        ];
    }
}
