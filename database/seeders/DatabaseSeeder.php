<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
                'name'     => 'Demo Admin',
                'email'    => 'admin@example.com',
                'password' => bcrypt('admin'),
                'is_admin' => 1,
            ]);
    }
}
