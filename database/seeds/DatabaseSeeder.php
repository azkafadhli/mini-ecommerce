<?php

use App\OrderDetails;
use App\UserAddress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(
            [
                RoleSeeder::class,
                UserSeeder::class,
                UserAddressSeeder::class,
                CategorySeeder::class,
                ProductSeeder::class,
                CategoryProductSeeder::class,
                CartItemSeeder::class,
                OrderSeeder::class,
                OrderDetailsSeeder::class
            ]
        );
    }
}
