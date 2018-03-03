<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class question_Back_type extends Model
{
    protected $table = 'question_bank_type';
    public $timestamps = false;
    protected $fillable  = ['q_bank_type_name'];
}
