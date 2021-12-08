<?php

use Illuminate\Database\Seeder;

class CartItemSeeder extends Seeder {
    public function run() {
        DB::table('cart_items')->insert(
            [
                ['product_id' => 1, 'user_id' => 2, 'quantity' => 1],
                ['product_id' => 2, 'user_id' => 2, 'quantity' => 1],
                ['product_id' => 4, 'user_id' => 2, 'quantity' => 2],
                ['product_id' => 4, 'user_id' => 3, 'quantity' => 1],
            ]
        );
    }
}
