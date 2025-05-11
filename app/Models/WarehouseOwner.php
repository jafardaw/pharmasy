<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class WarehouseOwner extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'username',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}