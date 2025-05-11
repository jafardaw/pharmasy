<?php

namespace Database\Seeders;

use App\Models\WarehouseOwner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WarehouseOwnerSeeder extends Seeder
{
    public function run()
    {
        WarehouseOwner::create([
            'name' => env('WAREHOUSE_OWNER_NAME', 'مدير المستودع'),
            'username' => env('WAREHOUSE_OWNER_USERNAME', 'admin'),
            'password' => Hash::make(env('WAREHOUSE_OWNER_PASSWORD', 'warehouse123'))
        ]);
    }
}