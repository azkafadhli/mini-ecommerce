<?php

use Illuminate\Database\Seeder;

class OrderDetailsSeeder extends Seeder {
    public function run() {
        DB::table('order_details')
            ->insert(
                [
                    [
                        'order_id' => 1,
                        'product_id' => 3,
                        'quantity' => 2,
                        'created_at' => now(),
                        'updated_at' => now()
                    ],
                ]
            );
    }
}
