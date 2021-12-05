<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('products')->insert(
            [
                [
                    'id' => 1,
                    'name' => 'Sony Playstation 5 - Playstation 5 - PS5 Disk Version - Sony Indonesia',
                    'price' => 13999000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => 2,
                    'name' => 'Monitor LED LG 29WP500 ultrawide IPS HDR freesync 29 inch 29wp500-b',
                    'price' => 3330000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => 3,
                    'name' => 'Clean Code A Handbook of Agile Software Craftsmanship',
                    'price' => 135000,
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'id' => 4,
                    'name' => 'Hotwheels VW Drag Bus MEA 2017 Limited Very Very Rare',
                    'price' => 12000000,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ]
        );
    }
}
