<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{

    protected $table = 'admin_users';

    protected $guarded = [
       'id'
    ];


    protected $hidden = [
        
    ];

}
