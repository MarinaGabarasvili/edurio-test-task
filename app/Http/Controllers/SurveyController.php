<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveAnswersRequest;
use App\Models\Survey;
use App\Services\SurveyService;
use Illuminate\Http\JsonResponse;

class SurveyController extends Controller
{
    private SurveyService $service;

    /**
     * @param SurveyService $service
     */
    public function __construct(SurveyService $service)
    {
        $this->service = $service;
    }


    /**
     * @param int $id
     * @return string
     */
    public function show(int $id): JsonResponse
    {
        $survey = $this->service->get($id);
        return response()->json($survey);
    }

    public function saveAnswers(SaveAnswersRequest $request, int $id): JsonResponse
    {

        $survey = Survey::withQuestionsAndOptions()->findOrFail($id);

        $this->service->saveAnswers($request->user(), $survey, $request->get('answers'));

        return response()->json([
            'success' => true
        ]);
    }
}
