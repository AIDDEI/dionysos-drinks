<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use App\Models\Flavor;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BeerController extends Controller
{
    public static function getBreweryData($id)
    {
        $brewery = DB::table('breweries')->where('id', '=', $id)->first();
        return $brewery;
    }

    public static function getSortData($id)
    {
        $sortId = DB::table('drink_sort')->where('drink_id', $id)->pluck('sort_id')->first();
        $sort = DB::table('sorts')->where('id', '=', $sortId)->first();
        return $sort;
    }

    public function index()
    {
        $beers = Drink::where('category', 'beer')->get()->shuffle();

        foreach ($beers as $beer) {
            $beer->brewery = self::getBreweryData($beer->brewery_id);
            $beer->sort = self::getSortData($beer->id);
        }

        return view('beers.beers', ['beers' => $beers]);
    }

    public function show($id)
    {
        $beer = Drink::find($id);

        if (!$beer) {
            return redirect()->route('beers.index');
        }

        $brewery = self::getBreweryData($beer->brewery_id);

        $sort = self::getSortData($beer->id);

        $flavorIds = DB::table('drink_flavor')->where('drink_id', $id)->pluck('flavor_id')->toArray();
        $flavors = Flavor::whereIn('id', $flavorIds)->get();

        $userRating = Rating::where('drink_id', $beer->id)
                        ->where('user_id', auth()->id())
                        ->first();

        return view('beers.show', compact('beer', 'brewery', 'sort', 'flavors', 'userRating'));
    }

    public function rate($id)
    {
        $beer = Drink::findOrFail($id);
        $brewery = self::getBreweryData($beer->brewery_id);
        $sort = self::getSortData($beer->id);
        $flavorIds = DB::table('drink_flavor')->where('drink_id', $id)->pluck('flavor_id')->toArray();
        $flavors = Flavor::whereIn('id', $flavorIds)->get();

        return view('ratings.rate', compact('beer', 'brewery', 'sort', 'flavors'));
    }

    public function editRating($id)
    {
        $rating = Rating::findOrFail($id);
        $beer = Drink::findOrFail($rating->drink_id);
        $brewery = self::getBreweryData($beer->brewery_id);
        $sort = self::getSortData($beer->id);
        $flavorIds = DB::table('drink_flavor')->where('drink_id', $beer->id)->pluck('flavor_id')->toArray();
        $flavors = Flavor::whereIn('id', $flavorIds)->get();

        return view('ratings.edit', compact('rating', 'beer', 'brewery', 'sort', 'flavors'));
    }
}
