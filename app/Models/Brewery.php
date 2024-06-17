<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brewery extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'year',
        'latitude',
        'longitude',
        'website',
    ];

    public function drinks()
    {
        return $this->hasMany(Drink::class);
    }
}
