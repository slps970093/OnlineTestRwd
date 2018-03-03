<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\testIteams as testIteamsEloquent;
use Auth;
class test_Items extends Model
{
    //
    protected $table = 'test_items';
    protected $fillable  = ['item_name','test_create_id','isRepeat','content'];

    public function test_Subject(){
        return $this->belongsTo(test_Subjects::class,'test_create_id');
    }
    public function question_item(){
        return $this->belongsTo(question_Back::class,'id');
    }
}
