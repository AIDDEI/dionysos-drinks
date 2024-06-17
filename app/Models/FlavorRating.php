<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlavorRating extends Model
{
    use HasFactory;
    protected $table = 'flavor_rating';
    protected $fillable = [
        'flavor_id',
        'rating_id',
        'rating',
    ];

    public function flavor()
    {
        return $this->belongsTo(Flavor::class);
    }
}
