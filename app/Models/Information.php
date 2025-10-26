<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Information extends Authenticatable
{

    protected $table = 'information';

    /*
    protected $fillable = [

    ];*/
    protected $guarded = [
        'id'
    ];

    protected $hidden = [

    ];

}
