<?php

namespace Database\Seeders;

use App\Models\Survey;
use Faker\Provider\Lorem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $survey = Survey::create([
            'name' => 'Servey 1',
            'description' => Lorem::sentence()
        ]);

        $optionTextArray = ['Very often', 'Quite often', 'Sometimes', 'Rarely', 'Never'];

        for ($i = 0; $i <= 8; $i++) {
            $question = $survey->questions()->create([
                'type' => 'radio',
                'text' => 'Question_'.$i+1
            ]);


            for ($j = 0; $j <= 4; $j++) {
                $question->options()->create([
                    'scalar_value' => $j,
                    'option_text' => $optionTextArray[$j]
                ]);
            }
        }

        $survey->questions()->create([
            'type' => 'text',
            'text' => 'Question 10'
        ]);
    }
}
