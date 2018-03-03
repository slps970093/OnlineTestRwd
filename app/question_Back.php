<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\QuestionBack as QuestionBackEloquent;
class question_Back extends Model
{
    //
    protected $table = 'question_bank';
    protected $fillable  = ['item_id','q_bank_type_id','question','anster','anster_type'];
    public function item(){
        return $this->hasOne('App\test_Items','item_id');
    }
    public function type(){
        return $this->hasOne('App\question_Back_type','q_bank_type_id');
    }
    public function options(){
        return $this->hasMany('App\question_option','question_id');
    }
    public function images(){
        return $this->hasOne('App\question_images','question_id');
    }


}
