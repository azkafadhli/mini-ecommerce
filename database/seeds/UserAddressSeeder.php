<?php

use App\UserAddress;
use Illuminate\Database\Seeder;

class UserAddressSeeder extends Seeder {
    public function run() {
        factory(UserAddress::class, 1)
            ->create(['is_main_address' => true, 'user_id' => 1]);
        factory(UserAddress::class, 1)
            ->create(['is_main_address' => true, 'user_id' => 2]);
        factory(UserAddress::class, 1)
            ->create(['is_main_address' => true, 'user_id' => 3]);
    }
}
