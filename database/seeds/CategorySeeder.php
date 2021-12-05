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
                ['id' => 1, 'name' => 'Hobbies'],
                ['id' => 2, 'name' => 'Electronics'],
                ['id' => 3, 'name' => 'Video Game Consoles'],
                ['id' => 4, 'name' => 'Books']
            ]
        );
    }
}
