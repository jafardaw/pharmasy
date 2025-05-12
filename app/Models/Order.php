<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['pharmacist_id', 'status','payment_status',];

    public function pharmacist()
    {
        return $this->belongsTo(Pharmacy::class, 'pharmacist_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}
