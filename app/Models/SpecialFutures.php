<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SpecialFutures extends Authenticatable
{

    protected $table = 'special_futures';

    /*
    protected $fillable = [
       
    ];*/
    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        
    ];

}
