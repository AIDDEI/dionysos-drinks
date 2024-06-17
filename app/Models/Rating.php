<?php

namespace App\Models;

use App\Models\Drink;
use App\Models\Sort;
use App\Models\FlavorRating;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'drink_id',
        'rating',
        'review',
    ];

    public function drink()
    {
        return $this->belongsTo(Drink::class, 'drink_id');
    }

    public function sort()
    {
        return $this->belongsTo(Sort::class);
    }

    public function flavorRatings()
    {
        return $this->hasMany(FlavorRating::class);
    }
}
