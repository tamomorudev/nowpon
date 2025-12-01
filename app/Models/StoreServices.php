<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StoreServices extends Authenticatable
{

    protected $table = 'store_services';

    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        
    ];

}
