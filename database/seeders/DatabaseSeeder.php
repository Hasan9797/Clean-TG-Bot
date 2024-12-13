<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Clean Service Admin',
            'phone' => '+998998115463',
            'email' => 'admin',
            'password' => 'admin123',
            'role' => 2,
        ]);
    }
}
