<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    use HasFactory;
    protected $fillable = [
        'brewery_id',
        'name',
        'description',
        'alcohol',
        'category',
        'image',
    ];

    public function category()
    {
        return $this->category;
    }

    public function brewery()
    {
        return $this->belongsTo(Brewery::class);
    }

    public function flavors()
    {
        return $this->belongsToMany(Flavor::class, 'drink_flavor', 'drink_id', 'flavor_id');
    }

    public function sorts()
    {
        return $this->belongsToMany(Sort::class, 'drink_sort', 'drink_id', 'sort_id');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
