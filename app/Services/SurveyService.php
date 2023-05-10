<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SurveyService
{
    private Survey $survey;

    /**
     * @param Survey $survey
     */
    public function __construct(Survey $survey)
    {
        $this->survey = $survey;
    }

    /**
     * @param int $id
     * @return Survey
     */
    public function get(int $id): Survey
    {
        return $this->survey->with('questions.options')->findOrFail($id);
    }

    /**
     * @param User $user
     * @param Survey $survey
     * @param array $answers
     * @return void
     */
    public function saveAnswers(User $user, Survey $survey, array $answers): void
    {
        $answers = array_map(function ($answer) use ($user, $survey) {
            return $this->formatData($user, $survey, $answer);
        }, $answers);

        $this->survey->answers()->insert($answers);
    }

    /**
     * @param User $user
     * @param Survey $survey
     * @param array $answer
     * @return array
     */
    private function formatData(User $user, Survey $survey, array $answer)
    {
        $question = $survey->questions->where('id', $answer['question_id'])->first();

        $item = [
            'user_id' => $user->id,
            'question_id' => $answer['question_id'],
            'answer_option_id' => null,
            'text' => null,
            'created_at' => Carbon::now()->toDateTime(),
            'updated_at' => Carbon::now()->toDateTime()
        ];

        if($question->type === 'text'){
            $item['text'] = $answer['text'];
        } else if ($question->type === 'radio'){
            if(in_array((int)$answer['answer_option_id'], $question->options->pluck('id')->toArray())){
                $item['answer_option_id'] = (int)$answer['answer_option_id'];
            } else {
                throw new ModelNotFoundException("The option not corresponding to question");
            }

        }

        return $item;
    }
}
