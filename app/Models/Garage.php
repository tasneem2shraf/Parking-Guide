<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Garage extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id', 'city', 'street', 'b_number', 'capacity', 'name', 'owner_id', 'lat', 'long'
    ];

    public function user_reviews()
    {
        return $this->belongsToMany(User::class, 'reviews', 'garage_id', 'user_id')
            ->withPivot('review')
            ->withTimestamps();
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }
}
