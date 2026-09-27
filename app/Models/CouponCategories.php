<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class CouponCategories extends Authenticatable
{

    protected $table = 'coupon_categories';

    /*
    protected $fillable = [
       
    ];*/
    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        
    ];

}
