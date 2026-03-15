<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;  // Add this import to use Str::slug()

class StatesSeeder extends Seeder
{
    public function run()
    {
        $states = [
            ['name' => 'Alabama'],
            ['name' => 'Alaska'],
            ['name' => 'Arizona'],
            ['name' => 'Arkansas'],
            ['name' => 'California'],
            ['name' => 'Colorado'],
            ['name' => 'Connecticut'],
            ['name' => 'Delaware'],
            ['name' => 'Florida'],
            ['name' => 'Georgia'],
            ['name' => 'Hawaii'],
            ['name' => 'Idaho'],
            ['name' => 'Illinois'],
            ['name' => 'Indiana'],
            ['name' => 'Iowa'],
            ['name' => 'Kansas'],
            ['name' => 'Kentucky'],
            ['name' => 'Louisiana'],
            ['name' => 'Maine'],
            ['name' => 'Maryland'],
            ['name' => 'Massachusetts'],
            ['name' => 'Michigan'],
            ['name' => 'Minnesota'],
            ['name' => 'Mississippi'],
            ['name' => 'Missouri'],
            ['name' => 'Montana'],
            ['name' => 'Nebraska'],
            ['name' => 'Nevada'],
            ['name' => 'New Hampshire'],
            ['name' => 'New Jersey'],
            ['name' => 'New Mexico'],
            ['name' => 'New York'],
            ['name' => 'North Carolina'],
            ['name' => 'North Dakota'],
            ['name' => 'Ohio'],
            ['name' => 'Oklahoma'],
            ['name' => 'Oregon'],
            ['name' => 'Pennsylvania'],
            ['name' => 'Rhode Island'],
            ['name' => 'South Carolina'],
            ['name' => 'South Dakota'],
            ['name' => 'Tennessee'],
            ['name' => 'Texas'],
            ['name' => 'Utah'],
            ['name' => 'Vermont'],
            ['name' => 'Virginia'],
            ['name' => 'Washington'],
            ['name' => 'West Virginia'],
            ['name' => 'Wisconsin'],
            ['name' => 'Wyoming'],
        ];

        // Generate the slug for each state
        foreach ($states as &$state) {
            $state['slug'] = Str::slug($state['name']);  // Generate slug from state name
        }

        // Insert states into the states table
        DB::table('states')->insert($states);
    }
}