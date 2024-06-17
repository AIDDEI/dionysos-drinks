<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\FlavorRating;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function store(Request $request, $id)
    {
        $validatedData = $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
            'review' => 'nullable|string',
            'flavor_ratings.*' => 'nullable|in:0,1,2',
        ]);

        $rating = new Rating();
        $rating->user_id = auth()->id();
        $rating->drink_id = $id;
        $rating->rating = $validatedData['rating'];
        $rating->review = $validatedData['review'];
        $rating->save();

        if ($request->filled('flavor_ratings'))
        {
            foreach ($request->flavor_ratings as $flavor_id => $flavor_rating)
            {
                $flavorRating = new FlavorRating();
                $flavorRating->rating_id = $rating->id;
                $flavorRating->flavor_id = $flavor_id;
                $flavorRating->rating = $flavor_rating;
                $flavorRating->save();
            }
        }

        return redirect()->route('beers.show', $id)->with('success', 'Rating successfully saved.');
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'rating' => 'required|numeric|min:0|max:10',
            'review' => 'nullable|string',
            'flavor_ratings.*' => 'nullable|in:0,1,2',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->rating = $validatedData['rating'];
        $rating->review = $validatedData['review'];
        $rating->save();

        FlavorRating::where('rating_id', $rating->id)->delete();

        if ($request->filled('flavor_ratings'))
        {
            foreach ($request->flavor_ratings as $flavor_id => $flavor_rating)
            {
                $flavorRating = new FlavorRating();
                $flavorRating->rating_id = $rating->id;
                $flavorRating->flavor_id = $flavor_id;
                $flavorRating->rating = $flavor_rating;
                $flavorRating->save();
            }
        }

        return redirect()->route('beers.show', $rating->drink_id)->with('success', 'Rating successfully updated.');
    }
}
