<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $rowCount = DB::table('roles')->count();
        // Check if the table is not empty before inserting data
        if ($rowCount === 0) {
            $roles = [
                ['id' => 1, 'name' => 'Admin'],
                ['id' => 2, 'name' => 'Moderator'],
                ['id' => 3, 'name' => 'User'],
            ];

            DB::table('roles')->insert($roles);
        } else {
            // Table is not empty, do not insert data again
            $this->command->info('Roles table already contains data. Seeder skipped.');
        }
    }
}
