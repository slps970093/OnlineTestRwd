<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\question_images as QuestionimagesEloquent;
class question_images extends Model
{
    //
    protected $table = 'question_images';
    protected $fillable  = ['question_id','file_path','file_name','title','content'];
    public function images(){
        return $this->belongTo(question_Back::class,'id');
    }
}
