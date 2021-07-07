<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rectangle extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'id', 'x1', 'y1', 'x2', 'y2', 'position', 'is_available', 'camera_id'
    ];

    public function camera()
    {
        return $this->belongTo(Camera::class);
    }
}
