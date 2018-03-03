<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionBankTable extends Migration
{
    /*
     * 題庫資料庫
     *
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id'); //測驗項目ID
            $table->integer('q_bank_type_id'); //類型
            $table->string('question');//題目
            $table->string('anster_type');
            $table->string('anster')->nullable(); //答案
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_bank');
    }
}
