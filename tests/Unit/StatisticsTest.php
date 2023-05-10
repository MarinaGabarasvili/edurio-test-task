<?php

namespace Tests\Unit;

use App\Models\User;
use Database\Seeders\AnswerSeeder;
use Database\Seeders\SurveySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_average_value(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed([
            SurveySeeder::class
        ]);

        $survey = $this->get('/api/surveys/1')->json();

        $answers = array_map(function ($question) use ($user) {
            $text = 'question text';
            $option_id = null;

            if ($question['type'] === 'radio') {
                $option_id = $question['options'][3]['id'];
            }

            return [
                'user_id' => $user->id,
                'answer_option_id' => $option_id,
                'question_id' => $question['id'],
                'text'       => $text
            ];
        }, $survey['questions']);

        $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response = $this->get('/api/surveys/1/statistics/average');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'    => 1,
            'question' => 'Question_1',
            'type'  => 'radio',
            'average' => 4
        ]);
    }

    /** @test */
    public function it_returns_question_count_value(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed([
            SurveySeeder::class
        ]);

        $survey = $this->get('/api/surveys/1')->json();

        $answers = array_map(function ($question) use ($user) {
            $text = 'question text';
            $option_id = null;

            if ($question['type'] === 'radio') {
                $option_id = $question['options'][3]['id'];
            }

            return [
                'user_id' => $user->id,
                'answer_option_id' => $option_id,
                'question_id' => $question['id'],
                'text'       => $text
            ];
        }, $survey['questions']);

        $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response = $this->get('/api/surveys/1/statistics/question-count');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id'    => 1,
            'question' => 'Question_1',
            'type'  => 'radio',
            'count' => 1
        ]);
    }

    /** @test */
    public function it_returns_option_count_value(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed([
            SurveySeeder::class
        ]);

        $survey = $this->get('/api/surveys/1')->json();

        $answers = array_map(function ($question) use ($user) {
            $text = 'question text';
            $option_id = null;

            if ($question['type'] === 'radio') {
                $option_id = $question['options'][0]['id'];
            }

            return [
                'user_id' => $user->id,
                'answer_option_id' => $option_id,
                'question_id' => $question['id'],
                'text'       => $text
            ];
        }, $survey['questions']);

        $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response = $this->get('/api/surveys/1/statistics/option-count');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'Very often'    => 1,
        ]);
    }

    /** @test */
    public function it_uses_incorrect_slug(): void
    {
        $this->seed([SurveySeeder::class]);
        $response = $this->get('/api/surveys/1/statistics/random-text');
        $response->assertStatus(404);
    }

}
