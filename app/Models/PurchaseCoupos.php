<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class PurchaseCoupos extends Authenticatable
{

    protected $table = 'purchase_coupons';

    /*
    protected $fillable = [
       
    ];*/
    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        
    ];

}
