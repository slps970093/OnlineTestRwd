<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionBankTypeTable extends Migration
{
    /*
     * 題目類型資料庫 (是非/選擇)
     *
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_bank_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('q_bank_type_name'); //題目類型
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('question_bank_type');
    }
}
