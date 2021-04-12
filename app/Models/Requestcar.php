<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestcar extends Model
{
    use HasFactory;
    protected $table = 'requests';
    public $timestamps = false;

    protected $fillable = [

        'id' ,	'user_id', 'garage_id','time_start','time_end', 'status'
     ];

     /**
  * The attributes that should be cast.
  * First change time zone to Africa/Cairo
  * @var array
  */
 protected $casts = [
     'time_start' => 'datetime:Y-m-d H:i',
     'time_end' => 'datetime:Y-m-d H:i',

 ];
}

