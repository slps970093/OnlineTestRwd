<?php

use Illuminate\Database\Seeder;
use App\User as UserEloquent;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        UserEloquent::create([
            'first_name' => '管理者',
            'last_name' => '系統',
            'username' => 'admin',
            'nickname' => '系統管理者',
            'email' => 'admin@admin.com',
            'password' => Hash::make('secret'),
            'isAdmin' => 0
        ]);
    }
}
