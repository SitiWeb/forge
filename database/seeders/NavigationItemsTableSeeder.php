<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class NavigationItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $items = [
            [
                'id' => 1,
                'title' => 'Servers',
                'url' => '/servers',
            ],
            [
                'id' => 2,
                'title' => 'Sites',
                'url' => '/sites',
            ],
            [
                'id' => 3,
                'title' => 'Import',
                'url' => '/imports',
            ],
            [
                'id' => 4,
                'title' => 'Users',
                'url' => '/users',
            ],
            [
                'id' => 5,
                'title' => 'Directadmin',
                'url' => '/directadmin',
            ],
            [
                'id' => 7,
                'title' => 'Databases',
                'url' => '/databases',
            ],
            [
                'id' => 8,
                'title' => 'Database users',
                'url' => '/databaseusers',
            ],
        ];

        DB::table('navigation_items')->insert($items);
    }
}
