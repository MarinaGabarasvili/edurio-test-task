<?php

namespace App\Services;

use App\Models\Survey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StatisticsService
{
    private SurveyService $survey;

    /**
     * @param SurveyService $survey
     */
    public function __construct(SurveyService $survey)
    {
        $this->survey = $survey;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getQuestionAverage(int $id): Collection
    {
        $survey = $this->survey->get($id);

        $questions = $survey->questions()
            ->select([
                'questions.id',
                'questions.text as question',
                'questions.type',
                DB::raw('AVG(answer_options.scalar_value) as average'),
            ])
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->leftJoin('answer_options', 'answer_options.id', '=', 'answers.answer_option_id')
            ->whereNotNull('answer_options.scalar_value')
            ->groupBy('questions.id')
            ->orderBy('questions.id', 'ASC')
            ->get();

        $questions->map(function ($question) {
            if ($question['average']) {
                $question['average'] = (($question['average'] - 0) / (4 - 0)) * (5 - 1) + 1;
            }

            return $question;
        });

        return $questions;
    }

    /**
     * @param int $id
     * @return Collection
     */
    public function getQuestionCount(int $id): Collection
    {
        $survey = $this->survey->get($id);

        $questions = $survey->questions()
            ->select([
                'questions.id',
                'questions.text as question',
                'questions.type',
                DB::raw('COUNT(answers.id) as count')
            ])
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->leftJoin('answer_options', 'answer_options.id', '=', 'answers.answer_option_id')
            ->groupBy('questions.id')
            ->orderBy('questions.id', 'ASC')
            ->get();

        return $questions;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getAnswersPerAnswerOption(int $id): array
    {
        $survey = $this->survey->get($id);

        $questions = $survey->questions()
            ->select([
                'questions.id',
                'questions.text as question',
                'answer_options.scalar_value as scalar_value',
                'answer_options.option_text as option',
                DB::raw('COUNT(answers.user_id) as count')
            ])
            ->leftJoin('answers', 'answers.question_id', '=', 'questions.id')
            ->leftJoin('answer_options', 'answer_options.id', '=', 'answers.answer_option_id')
            ->whereNotNull('answer_options.scalar_value')
            ->groupBy('questions.id', 'answer_options.scalar_value', 'answer_options.option_text')
            ->orderBy('questions.id', 'ASC')
            ->get();

        $formattedData = [];
        foreach ($questions as $question) {
            $formattedData[$question['question']][] = [
                $question['option'] => $question['count']
            ];
        }

        return $formattedData;
    }

}
