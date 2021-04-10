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
    
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
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

    //Relations

// retutn all comments of user garage using function : index() in ConmmentController
function comments(){

    return $this->hasMany('App\Models\Comment', 'garage_id');
}

// retutn all Requests of user garage using function : index() in RequestcarController
function requestcars(){

    return $this->hasMany('App\Models\Requestcar');
}

///////////////////////////////
public function user(){
    return $this->belongsTo('App\Models\User');
}


}
