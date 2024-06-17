<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('beers', [\App\Http\Controllers\BeerController::class, 'index']);
Route::get('beers/{id}', [\App\Http\Controllers\BeerController::class, 'show'])->name('beers.show');

Route::get('ratings/{id}', [\App\Http\Controllers\BeerController::class, 'rate'])->name('ratings.rate')->middleware('auth');
Route::post('ratings/{id}', [\App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store')->middleware('auth');
Route::get('ratings/{id}/edit', [\App\Http\Controllers\BeerController::class, 'editRating'])->name('ratings.edit')->middleware('auth');
Route::put('ratings/{id}', [\App\Http\Controllers\RatingController::class, 'update'])->name('ratings.update')->middleware('auth');

Route::get('recommendations', [\App\Http\Controllers\RecommendationController::class, 'index'])->middleware('auth');
Route::get('recommendations/no-ratings', [\App\Http\Controllers\RecommendationController::class, 'noRating'])->middleware('auth');
Route::get('recommendations/personalized', [\App\Http\Controllers\RecommendationController::class, 'personalized'])->name('recommendations.personalized')->middleware('auth');
Route::get('recommendations/adventurous', [\App\Http\Controllers\RecommendationController::class, 'adventurous'])->name('recommendations.adventurous')->middleware('auth');

Route::fallback(function () {
    \Log::info('Fallback route hit');
    return response()->json(['message' => 'Page Not Found.'], 404);
});

require __DIR__.'/auth.php';
