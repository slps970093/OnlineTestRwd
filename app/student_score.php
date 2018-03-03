<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\student_score as StudeintscoreEloquent;
class student_score extends Model
{
    //
    protected $table = 'student_score';
    protected $fillable   = ['item_id','uid','score'];

    public function item(){
        return $this->hasOne(test_Items::class,'id','item_id');
    }
    public function user(){
        return $this->hasOne(User::class,'id','uid');
    }
}
