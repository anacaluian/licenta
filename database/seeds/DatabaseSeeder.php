<?php

use Illuminate\Database\Seeder;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'Demo',
            'email' => 'admin@demo.com',
            'password' => Hash::make('admin'),
            'role' => 1
        ]);

        User::create([
        'first_name' => 'Member',
        'last_name' => 'Demo',
        'email' => 'member@demo.com',
        'password' => Hash::make('secret'),
        'role' => 2
    ]);
    }
}
