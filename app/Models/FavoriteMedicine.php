<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteMedicine extends Model
{
protected $fillable = ['pharmacy_id', 'medicine_id'];

public function pharmacy()
{
    return $this->belongsTo(Pharmacy::class);
}

public function medicine()
{
    return $this->belongsTo(Medicine::class);
}
}
