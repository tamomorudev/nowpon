<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Zipcodes extends Authenticatable
{

    protected $table = 'zipcodes';

    protected $guarded = [
       'id'
    ];


    protected $hidden = [
        
    ];

}
