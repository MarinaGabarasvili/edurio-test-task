<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\User;
use Faker\Provider\Lorem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = $this->getUsers();
        $survey = $this->getSurvey();

        $answers = $this->prepareData($survey, $users);

        $answerChunks = array_chunk($answers, 500);

        foreach ($answerChunks as $answerChunk) {
            DB::table('answers')->insert($answerChunk);
        }
    }

    private function getSurvey()
    {
        return Survey::with('questions.options')->first();
    }

    private function getUsers()
    {
        return User::all();
    }

    private function prepareData($survey, $users)
    {
        $answers = [];

        foreach ($users as $user) {
            foreach ($survey->questions as $question) {
                $answer = [
                    'question_id' => $question->id,
                    'answer_option_id' => null,
                    'user_id' => $user->id,
                    'text' => null
                ];

                if($question->type === 'radio'){
                    $option = $question->options->random();
                    $answer['answer_option_id'] = $option->id;
                } else if ($question->type === 'text') {
                    $answer['text'] = Lorem::sentence(3);
                }

                $answers[] = $answer;
            }
        }

        return $answers;
    }

}
