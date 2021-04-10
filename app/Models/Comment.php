<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;


    protected $fillable = [
        'id','user_id',  'garage_id', 'comment'
     ];
 
     /**
      * The attributes that should be cast.
      *
      * @var array
      */
     protected $casts = [
         'created_at' => 'datetime:Y-m-d',
         'updated_at' => 'datetime:Y-m-d',
     ];
 
     public function User(){
         return $this->belongsTo('App\Models\User');
     }
 
     public function garage() {
         return $this->belongsTo(Garage::class);
     }
}




