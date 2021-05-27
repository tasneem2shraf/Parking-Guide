<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floorhistory extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id', 'num_cars', 'parking_time', 'floor_id'
    ];

    public function floor()
    {
        return $this->belongTo(Floor::class);
    }
}
