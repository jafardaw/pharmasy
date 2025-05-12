<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'scientific_name',
        'commercial_name',
        'category',
        'manufacturer',
        'quantity',
        'expiry_date',
        'price'
    ];
}