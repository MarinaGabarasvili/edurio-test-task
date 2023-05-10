<?php

namespace App\Http\Controllers;

use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use function Symfony\Component\Translation\t;

class StatisticsController extends Controller
{
    private StatisticsService $service;

    /**
     * @param StatisticsService $service
     */
    public function __construct(StatisticsService $service)
    {
        $this->service = $service;
    }

    public function getStatistics($id, $slug):JsonResponse
    {
        $results = null;
        if($slug === 'average'){
            $results = $this->service->getQuestionAverage($id);
        } else if($slug === 'question-count'){
            $results = $this->service->getQuestionCount($id);
        } else if($slug === 'option-count'){
            $results = $this->service->getAnswersPerAnswerOption($id);
        } else{
            return abort(404);
        }

        return response()->json($results);
    }
}
