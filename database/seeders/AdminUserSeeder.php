<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin user
        DB::table('users')->insert([
            'name' => 'Admin User',
            'email' => 'admin@example.com', // Change to your preferred email
            'password' => Hash::make('password'), // Change to your preferred password
            'role' => 'admin', // Assuming you have a role column in your users table
            'remember_token' => Str::random(10),
        ]);
    }
}