<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentFillIn extends Migration
{   
    /*
     * 學生填寫記錄資料庫
     *
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_fullin', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('uid');
            $table->integer('qb_id');
            $table->integer('item_id'); //測驗項目ID
            $table->integer('q_bank_type_id'); //類型
            $table->string('question');//題目
            $table->string('anster_type');
            $table->string('correct_answer');
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
        Schema::dropIfExists('student_fullin');
    }
}
