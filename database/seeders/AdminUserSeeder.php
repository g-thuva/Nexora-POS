<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@nexora.com';
        $name = 'admin';
        $username = 'admin';
        $password = 'Test@1234';

        // Create or update the admin user
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'username' => $username,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
            ]
        );

        // Ensure password is set (in case updateOrCreate didn't update it)
        if (!Hash::check($password, $user->password)) {
            $user->password = Hash::make($password);
            $user->save();
        }
    }
}
