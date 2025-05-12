<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pharmacy extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'pharmacy_name',
        'region',
        'phone',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function orders()
{
    return $this->hasMany(Order::class, 'pharmacist_id');
}
public function favorites()
{
    return $this->belongsToMany(Medicine::class, 'favorite_medicines')->withTimestamps();
}




}