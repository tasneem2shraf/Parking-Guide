<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestcar extends Model
{
    use HasFactory;

    protected $fillable = [
        
        'id' ,	'user_id', 'garage_id','time_start','time_end', 'status'
     ];
 
     /**
  * The attributes that should be cast.
  *
  * @var array
  */
 protected $casts = [
     'time_start' => 'datetime:Y-m-d',
     'time_end' => 'datetime:Y-m-d',
     'created_at' => 'datetime:Y-m-d',
     'updated_at' => 'datetime:Y-m-d',
 ];
}

