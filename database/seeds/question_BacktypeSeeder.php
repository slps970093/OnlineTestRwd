<?php

use Illuminate\Database\Seeder;
use App\question_Back_type as QuestionBacktypeEloquent;

class question_BacktypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        QuestionBacktypeEloquent::create([
            'q_bank_type_name' => '是非'
        ]);
        QuestionBacktypeEloquent::create([
            'q_bank_type_name' => '單選'
        ]);
        QuestionBacktypeEloquent::create([
            'q_bank_type_name' => '複選'
        ]);
    }
}
