<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(question_BacktypeSeeder::class);
        $this->call(userSeeder::class);
        $this->call(websiteSeeder::class);
    }
}
