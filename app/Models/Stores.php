<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Stores extends Authenticatable
{

    protected $table = 'stores';

    protected $fillable = [
       
    ];

    protected $hidden = [
        
    ];

}
