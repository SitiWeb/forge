<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\User;
use Illuminate\Support\Facades\Hash;
use DB;

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
        // Check if the table is not empty before inserting data
        $rowCount = DB::table('users')->count();
        if ($rowCount === 0) {
            $user = User::create([
                'name' => $defaultUser,
                'email' => $defaultEmail,
                'password' => Hash::make($defaultPassword),
            ]);

            // Assuming you have a 'roles' relationship defined in your User model
            $user->roles()->attach([1, 2, 3]);
        } else {
            // Table is not empty, do not insert data again
            $this->command->info('Roles table already contains data. Seeder skipped.');
        }
    }
}
