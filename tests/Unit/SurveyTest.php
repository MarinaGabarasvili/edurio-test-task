<?php

namespace Tests\Unit;

use App\Models\Survey;
use App\Models\User;
use App\Services\SurveyService;
use Database\Seeders\SurveySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class SurveyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_a_survey(): void
    {
        $this->seed(SurveySeeder::class);

        $response = $this->get('/api/surveys/1');

        $response->assertStatus(200);
        $response->assertJsonPath('id', 1);
    }

    /** @test */
    public function it_can_save_survey_answers_with_correct_data(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed(SurveySeeder::class);

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

        $response = $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /** @test */
    public function it_can_not_save_survey_answers_with_incorrect_option_id_data(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed(SurveySeeder::class);

        $survey = $this->get('/api/surveys/1')->json();

        $answers = array_map(function ($question) use ($user) {
            $text = 'question text';
            $option_id = null;

            if ($question['type'] === 'radio') {
                $option_id = 192;
            }

            return [
                'user_id' => $user->id,
                'answer_option_id' => $option_id,
                'question_id' => $question['id'],
                'text'       => $text
            ];
        }, $survey['questions']);

        $response = $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response->assertStatus(422);
        $response->assertSeeText('is invalid.');
    }

    /** @test */
    public function it_can_not_save_survey_answers_with_incorrect_question_id_data(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed(SurveySeeder::class);

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
                'question_id' => 18273,
                'text'       => $text
            ];
        }, $survey['questions']);

        $response = $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response->assertStatus(422);
        $response->assertSeeText('is invalid.');
    }

    /** @test */
    public function it_can_not_save_survey_answers_with_incorrect_user_id_data(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed(SurveySeeder::class);

        $survey = $this->get('/api/surveys/1')->json();

        $answers = array_map(function ($question) use ($user) {
            $text = 'question text';
            $option_id = null;

            if ($question['type'] === 'radio') {
                $option_id = $question['options'][0]['id'];
            }

            return [
                'user_id' => 263,
                'answer_option_id' => $option_id,
                'question_id' => $question['id'],
                'text'       => $text
            ];
        }, $survey['questions']);

        $response = $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response->assertStatus(422);
        $response->assertSeeText('is invalid.');
    }

    /** @test */
    public function it_returns_a_survey_with_questions_and_options(): void
    {
        $this->seed(SurveySeeder::class);

        $service = new SurveyService($this->app->make(Survey::class));
        $survey = $service->get(1);

        $this->assertEquals(1, $survey->id);
        $this->assertCount(10, $survey->questions);
        $this->assertCount(5, $survey->questions[0]->options);
    }

    /** @test */
    public function it_can_save_survey_answers(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed(SurveySeeder::class);

        $service = new SurveyService($this->app->make(Survey::class));
        $survey = $service->get(1);

        $answers = $survey->questions->map(function ($question) use ($user) {
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
        })->toArray();

        $service->saveAnswers($user, $survey, $answers);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_with_incorrect_option_id_data(): void
    {
        $user = User::factory()->count(1)->create()[0];

        $this->seed(SurveySeeder::class);

        $survey = $this->get('/api/surveys/1')->json();

        $answers = array_map(function ($question) use ($user) {
            $text = 'question text';
            $option_id = null;

            if ($question['type'] === 'radio') {
                $option_id = 36;
            }

            return [
                'user_id' => $user->id,
                'answer_option_id' => $option_id,
                'question_id' => $question['id'],
                'text'       => $text
            ];
        }, $survey['questions']);

        $response = $this->actingAs($user)->json('POST', '/api/surveys/1', [
            'answers' => $answers
        ]);

        $response->assertStatus(500);
    }
}
