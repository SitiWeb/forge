<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $defaultUser = env('DEFAULT_USER', 'admin');
        $defaultEmail = env('DEFAULT_EMAIL', 'info@site.com');
        $defaultPassword = env('DEFAULT_PASS', 'changeme');

        $user = User::create([
            'name' => $defaultUser,
            'email' => $defaultEmail,
            'password' => Hash::make($defaultPassword),
        ]);

        // Assuming you have a 'roles' relationship defined in your User model
        $user->roles()->attach([1, 2, 3]);
    }
}
