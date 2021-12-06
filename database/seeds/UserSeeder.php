<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $admin = [
            'role_id' => 1,
            'name' => 'Aidan Brown',
            'email' => 'aidan.brown@example.com'
        ];
        $customer = [
            'role_id' => 2,
            'name' => 'Karolann Windler',
            'email' => 'karolann.windler@example.com'
        ];
        factory(User::class, 1)->create($admin);
        factory(User::class, 1)->create($customer);
    }
}
