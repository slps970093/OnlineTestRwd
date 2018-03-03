<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class question_option extends Model
{
    //
    protected $table = 'question_option';
    protected $fillable  = ['question_id','option_name'];
    public function options(){
        return $this->belongTo(question_Back::class,'id');
    }
}
