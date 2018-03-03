<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\testSubjects as testSubjectsEloquent;
class test_Subjects extends Model
{
    //
    protected $table = 'test_subjects';
    protected $fillable  = ['subjects_name'];

    public function test_items(){
        $this->hasMary(test_items::class);
    }
}
