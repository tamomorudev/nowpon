<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Coupons extends Authenticatable
{

    protected $table = 'coupons';

    protected $fillable = [
       
    ];

    protected $hidden = [
        
    ];

}
