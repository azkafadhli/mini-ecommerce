<?php

use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder {
    public function run() {
        DB::table('orders')
            ->insert(
                [
                    [
                        'user_addresses_id' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]
            );
    }
}
