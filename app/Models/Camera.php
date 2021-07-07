<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Camera extends Model
{
    use HasFactory;
   public $timestamps = false;

    protected $fillable = [
        'id' ,'image', 'title', 'floor_id'
     ];
     
     public function rectangles()
     {
         return $this->hasMany(Rectangle::class);
     }

}
