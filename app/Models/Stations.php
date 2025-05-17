<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stations extends Authenticatable
{

    protected $table = 'stations';

    protected $guarded = [
       'id'
    ];


    protected $hidden = [
        
    ];

}
