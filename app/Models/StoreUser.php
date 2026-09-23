<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class StoreUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'store_user';

    protected static function booted()
    {
        // 一覧だけでなく、既存セッション・再ログインでも削除済みユーザーを除外する。
        static::addGlobalScope('active', function ($query) {
            $query->where('store_users.delete_flg', 0);
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}
