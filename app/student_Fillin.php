<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\test_Items;
use App\studentFillin as StudeinFillinEloquent;
class student_Fillin extends Model
{
    //
    protected $table = 'student_fullin';
    protected $fillable  = ['uid','qb_id','anster','item_id','q_bank_type_id','question','anster_type','correct_answer'];
    public function item(){
        return $this->hasOne('App\test_Items','id','item_id');
    }
    public function type(){
        return $this->hasOne('App\question_Back_type','id','q_bank_type_id');
    }
    public function options(){
        return $this->hasMany('App\question_option','question_id','qb_id');
    }
    public function images(){
        return $this->hasOne('App\question_images','question_id','qb_id');
    }

}
