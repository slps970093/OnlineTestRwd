<?php

use Illuminate\Database\Seeder;
use App\webSite as webSiteEloquent;
class websiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        webSiteEloquent::create([
            'website_name' => '線上測驗系統',
            'description' => '這是一個線上測驗系統，使用Laravel Framework製作',
            'keyword' => 'Laravel,線上,測驗'
        ]);
    }
}
