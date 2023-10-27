<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Artisan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Run the seeders you want here
        Artisan::call('db:seed', ['--class' => 'RolesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        Artisan::call('db:seed', ['--class' => 'NavigationItemsTableSeeder']);
        
        // Add more seeders as needed
    }
}
