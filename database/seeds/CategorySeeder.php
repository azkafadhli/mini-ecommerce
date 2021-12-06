<?php

use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('categories')->insert(
            [
                ['name' => 'Hobbies'],
                ['name' => 'Electronics'],
                ['name' => 'Video Game Consoles'],
                ['name' => 'Books']
            ]
        );
    }
}
