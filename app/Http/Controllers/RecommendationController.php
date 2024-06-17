<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;

use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index()
    {
        return view('recommendations.recommendations');
    }

    public function noRating()
    {
        return view('recommendations.no-ratings');
    }

    public function personalized()
    {
        // Get the User Id
        $userId = auth()->id();

        // Get the personalized recommendations from the RecommendationService
        $recommendations = $this->recommendationService->getPersonalizedRecommendations($userId, 'beer');

        // Check if there are no recommendations and redirect to no-ratings if necessary
        if (is_null($recommendations) || empty($recommendations)) {
            return redirect()->route('recommendations.no-ratings');
        }

        // Pass the recommendations to the view
        return view('recommendations.personalized.personalized', compact('recommendations'));
    }

    public function adventurous()
    {
        // Get the User ID
        $userId = auth()->id();

        // Get the adventurous recommendations from the RecommendationService
        $adventurousRecommendations = $this->recommendationService->getAdventurousRecommendations($userId, 'beer');

        // Check if there are no recommendations and redirect to no-ratings if necessary
        if (is_null($adventurousRecommendations)) {
            return redirect()->route('recommendations.no-ratings');
        }

        // Pass the recommendations to the view
        return view('recommendations.adventurous.adventurous', compact('adventurousRecommendations'));
    }
}
