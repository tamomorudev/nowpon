<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StripeLogs extends Authenticatable
{

    protected $table = 'stripe_logs';

    /*
    protected $fillable = [
       
    ];*/
    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        
    ];

}
