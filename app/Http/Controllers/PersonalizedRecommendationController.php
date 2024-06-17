<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Drink;
use App\Models\Sort;

use Illuminate\Http\Request;

class PersonalizedRecommendationController extends Controller
{
    public function getPersonalizedRecommendations(int $userId, string $category): Collection
    {
        // Get all ratings with an 8 or higher
        $ratings = Rating::where('user_id', $userId)
            ->whereHas('drink', function ($query) {
                $query->where('category', $category);
            })
            ->where('rating', '>=', 8)
            ->with('sort')
            ->get();
        
        // If there are no ratings of 8 or higher, get the ratings of 6 or higher
        if($ratings->isEmpty()) {
            $ratings = Rating::where('user_id', $userId)
                ->whereHas('drink', function ($query) {
                    $query->where('category', $category);
                })
                ->where('rating', '>=', 6)
                ->with('sort')
                ->get();
        }

        // If there are still nog ratings, go to another page
        if($ratings->isEmpty()) {
            return view('recommendations.no-ratings');
        }

        // Group the rating based on their sort
        $groupedRatings = $ratings->groupBy('sort_id');

        // Make a collection of quantities per sort_id
        $sortCounts = $groupedRatings->map(function ($items) {
            return $items->count();
        });

        // Sort the collection by number, so that te most common type is at top
        $sortedSortCounts = $sortCounts->sort();

        // Pick the most common
        $mostCommonSort = $sortedSortCounts->keys()->last();

        // If several sorts occur equally often, select random
        $mostCommonSorts = $sortedSortCounts->filter(function ($count, $sortId) use ($sortedSortCounts, $mostCommonSort) {
            return $count === $sortedSortCounts->get($mostCommonSort);
        })->keys();

        $mostCommonSort = $mostCommonSorts->random();

        // Get the ratings for the most common type of drink only
        $filteredRatings = $ratings->filter(function ($rating) use ($mostCommonSort) {
            return $rating->sort_id === $mostCommonSort;
        });

        // Get the corresponding sort
        $mostCommon = Sort::findOrFail($mostCommonSort);

        // Collect all drinks from the most common sort
        $drinks = $mostCommon->drinks;

        // Collect all flavors from these drinks
        $flavors = $drinks->flatMap->flavors;

        // Group the flavor ratings on flavor
        $groupedFlavorRatings = $filteredRatings->flatMap->flavorRatings->groupBy('flavor_id');

        // Make a collection of total ratings per flavor
        $totalFlavorRatings = $groupedFlavorRatings->map(function ($items) {
            return $items->sum('rating');
        });

        // Adjust the values 0, 1 and 2 to -1, +1 and +2 and add them together
        $totalFlavorCounts = $totalFlavorRatings->map(function ($totalRating) {
            if ($totalRating === 0) {
                return -1;
            } elseif ($totalRating === 1) {
                return 1;
            } else {
                return 2;
            }
        });

        // Sort the total flavor ratings in descending order
        $sortedFlavorCounts = $totalFlavorCounts->sortDesc();

        // Select the 3 highest flavors
        $topThreeFlavors = $sortedFlavorCounts->take(3);

        // Retrieve the drinks that the user has already rated
        $ratedDrinks = $filteredRatings->map->drink;

        // Filter the drinks by those that the user has not drank yet
        $newDrinks = $drinks->reject(function ($drink) use ($ratedDrinks) {
            return $ratedDrinks->contains($drink);
        });

        // Make an array for the recommended drink
        $recommendations = [];

        // Loop through each new drink
        foreach ($newDrinks as $newDrink) {
            // Initialize a counter for the number of matching flavors
            $matchCount = 0;

            // Loop trough all the flavors of the drink
            foreach ($newDrink->flavors as $flavor) {
                // Check if the flavor matches one of the top three flavors
                if ($topThreeFlavors->contains($flavor)) {
                    $matchCount++;
                }
            }

            // Calculate the average overall rating of the drink
            $averageRating = $newDrink->ratings->avg('rating');

            // Add the drink to the recommendations
            $recommendations[] = [
                'drink' => $newDrink,
                'matchCount' => $matchCount,
                'averageRating' => $averageRating,
            ];
        }

        // Sort the recommendations by number of matching flavors and average overall rating
        usort($recommendations, function($a, $b) {
            if ($a['matchCount'] == $b['matchCount']) {
                return $b['averageRating'] <=> $a['averageRating'];
            }
            return $b['matchCount'] <=> $a['matchCount'];
        });

        // Get the best recommendation and return
        $bestRecommendation = !empty($recommendations) ? $recommendations[0]['drink'] : null;
        return $bestRecommendation;
    }
}
