<?php

namespace App\Services;

use App\Models\Rating;
use App\Models\Drink;
use App\Models\FlavorRating;
use App\Models\Flavor;

class RecommendationService
{
    // Function to get the personalized recommendations
    public function getPersonalizedRecommendations(int $userId, string $category): ?array
    {
        // Get all ratings with an 8 or higher
        $ratings = Rating::join('drink_sort', 'ratings.drink_id', '=', 'drink_sort.drink_id')
            ->where('user_id', $userId)
            ->whereHas('drink', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->where('rating', '>=', 8)
            ->with('sort')
            ->get();

        // If there are no ratings of 8 or higher, get the ratings of 6 or higher
        if ($ratings->isEmpty()) {
            $ratings = Rating::join('drink_sort', 'ratings.drink_id', '=', 'drink_sort.drink_id')
                ->where('user_id', $userId)
                ->whereHas('drink', function ($query) use ($category) {
                    $query->where('category', $category);
                })
                ->where('rating', '>=', 6)
                ->with('sort')
                ->get();
        }

        // If there are still no ratings, return null
        if ($ratings->isEmpty()) {
            return null;
        }

        // Group the rating based on their sort
        $groupedRatings = $ratings->groupBy('sort_id');

        // Make a collection of quantities per sort_id
        $sortCounts = $groupedRatings->map(function ($items) {
            return $items->count();
        });

        // Sort the collection by number, so that the most common type is at the top
        $sortedSortCounts = $sortCounts->sort()->toArray();
        arsort($sortedSortCounts);

        // Loop through each sort and collect recommendations
        $recommendations = [];
        foreach ($sortedSortCounts as $sortId => $count) {
            // Get the ratings for the most common type of drink only
            $filteredRatings = $ratings->filter(function ($rating) use ($sortId) {
                return $rating->sort_id === $sortId;
            });

            // Collect all drinks from the most common sort
            $drinks = Drink::whereHas('sorts', function ($query) use ($sortId) {
                $query->where('sort_id', $sortId);
            })->with('brewery')->get();

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

            // Select the highest flavors, with a maximum of 3
            $topFlavorCount = min($sortedFlavorCounts->count(), 3);
            $topFlavors = $sortedFlavorCounts->take($topFlavorCount)->keys()->toArray();

            // Retrieve the drinks that the user has already rated
            $ratedDrinks = collect($filteredRatings->pluck('drink_id')->toArray());

            // Filter the drinks by those that the user has not drank yet
            $newDrinks = $drinks->reject(function ($drink) use ($ratedDrinks) {
                return $ratedDrinks->contains($drink->id);
            });

            // Make an array for the recommended drink
            $sortRecommendations = [];

            // Loop through each new drink
            foreach ($newDrinks as $newDrink) {
                // Initialize a counter for the number of matching flavors
                $matchCount = 0;

                // Loop through all the flavors of the drink
                foreach ($newDrink->flavors as $flavor) {
                    // Check if the flavor matches one of the top three flavors
                    if (in_array($flavor->id, $topFlavors)) {
                        $matchCount++;
                    }
                }

                // Calculate the average overall rating of the drink
                $newRatings = $newDrink->ratings;

                if ($newRatings->isEmpty()) {
                    $averageRating = 7;
                } else {
                    $averageRating = $newRatings->avg('rating');
                }

                // Add the drink to the sort recommendations
                $sortRecommendations[] = [
                    'drink' => $newDrink,
                    'matchCount' => $matchCount,
                    'averageRating' => $averageRating,
                ];
            }

            // Sort the sort recommendations by number of matching flavors and average overall rating
            usort($sortRecommendations, function ($a, $b) {
                if ($a['matchCount'] == $b['matchCount']) {
                    return $b['averageRating'] <=> $a['averageRating'];
                }
                return $b['matchCount'] <=> $a['matchCount'];
            });

            // Take the top 2 recommendations from the sort
            $recommendations = array_merge($recommendations, array_slice($sortRecommendations, 0, 2));

            // Break the loop if we already have 5 recommendations
            if (count($recommendations) >= 5) {
                break;
            }
        }

        // Return the top 5 recommendations
        return array_slice($recommendations, 0, 5);
    }

    // Function to get adventurous recommendations
    public function getAdventurousRecommendations(int $userId, string $category): ?array
    {
        // Get all ratings with an 8 or higher
        $ratings = Rating::where('user_id', $userId)
            ->whereHas('drink', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->where('rating', '>=', 8)
            ->get();

        // If there are no ratings of 8 or higher, get the ratings of 6 or higher
        if ($ratings->isEmpty()) {
            $ratings = Rating::where('user_id', $userId)
                ->whereHas('drink', function ($query) use ($category) {
                    $query->where('category', $category);
                })
                ->where('rating', '>=', 6)
                ->get();
        }

        // If there are still nog ratings, return null to go to another page
        if ($ratings->isEmpty()) {
            return null;
        }

        // Get the ID's from the drinks
        $drinkIds = $ratings->pluck('drink_id');

        // Get all drinks based on the drinks ID's
        $drinks = Drink::whereIn('id', $drinkIds)->get();

        // Collect all the flavors from these drinks
        $flavors = $drinks->flatMap->flavors;

        // Get the ID's of the flavors
        $flavorIds = $flavors->pluck('id');

        // Get all flavor ratings based on the flavor ID's
        $flavorRatings = FlavorRating::whereIn('flavor_id', $flavorIds)->get();

        // Group the flavor ratings by the flavor ID
        $groupedFlavorRatings = $flavorRatings->groupBy('flavor_id');

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

        // Select the highest flavors, with a maximum of 3
        $topFlavorCount = min($sortedFlavorCounts->count(), 3);
        $topFlavors = $sortedFlavorCounts->take($topFlavorCount);

        // Get the ID's of the top flavors
        $topFlavorIds = $topFlavors->keys();

        // Get the top flavors based on their ID's
        $topFlavors = Flavor::whereIn('id', $topFlavorIds)->get();

        // Get all drinks from the specified category
        $allDrinks = Drink::where('category', $category)->get();

        if ($allDrinks->isEmpty()) {
            return null;
        }

        // Filter the drinks to exclude those that the user already has rated
        $newDrinks = $allDrinks->reject(function ($drink) use ($ratings) {
            return $ratings->contains('drink_id', $drink->id);
        });

        if ($newDrinks->isEmpty()) {
            return null;
        }

        // Array for the recommendations
        $recommendations = [];

        // Loop through each drink
        foreach ($newDrinks as $newDrink) {
            // Initialize a counter for the number of matching flavors
            $matchCount = 0;

            // Loop trough all the flavors of the drink
            foreach ($newDrink->flavors as $flavor) {
                // Check if the flavor matches one of the top three flavors
                if ($topFlavors->contains($flavor)) {
                    $matchCount++;
                }
            }

            // Add the drink to the recommendations
            $recommendations[] = [
                'drink' => $newDrink,
                'matchCount' => $matchCount,
            ];
        }

        // Sort the recommendations by number of matching flavors and average overall rating
        usort($recommendations, function ($a, $b) {
            return $b['matchCount'] <=> $a['matchCount'];
        });

        // Select the top 5 recommended drinks
        $topRecommendations = array_slice($recommendations, 0, 5);

        // Return the recommendations
        return $topRecommendations;
    }
}