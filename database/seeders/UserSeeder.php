<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create single admin user with the credentials requested
        User::updateOrCreate(
            ['email' => 'admin@nexora.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@nexora.com',
                'password' => bcrypt('Test@1234'),
                'role' => 'admin',
            ]
        );
    }
}
