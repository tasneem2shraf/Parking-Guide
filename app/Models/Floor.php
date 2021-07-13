<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id', 'capacity', 'number','garage_id'
    ];

    public function garage_floors()
    {
        return $this->belongTo(Garage::class);
    }

    public function floor_histories()
    {
        return $this->hasMany(Floorhistory::class);
    }

    public function cameras()
    {
        return $this->hasMany(Camera::class);
    }
}
