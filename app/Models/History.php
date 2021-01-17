<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    public $timestamps=false;

    protected $fillable = [
        'id', 'car_number', 'parking_time', 'garage_id'
    ];

    public function garage_histories()
    {
        return $this->belongTo(Garage::class);
    }
}
