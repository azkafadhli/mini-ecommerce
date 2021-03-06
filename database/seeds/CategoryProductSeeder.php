<?php

use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('category_product')->insert(
            [
                ['category_id' => 1, 'product_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['category_id' => 2, 'product_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['category_id' => 3, 'product_id' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['category_id' => 2, 'product_id' => 2, 'created_at' => now(), 'updated_at' => now()],
                ['category_id' => 1, 'product_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['category_id' => 4, 'product_id' => 3, 'created_at' => now(), 'updated_at' => now()],
                ['category_id' => 1, 'product_id' => 4, 'created_at' => now(), 'updated_at' => now()]
            ]
        );
    }
}
