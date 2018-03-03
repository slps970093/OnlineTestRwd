<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student_score as ScoreEloquent;
use App\test_Subjects as SubjectEloquent;
use App\webSite as webSiteEloquent;
use Auth;
use Illuminate\Support\Facades\Gate;
class StudentScoreController extends Controller
{
    public function admin_index($id){
        if(Auth::check() && Gate::allows('isAdmin')){
            if(is_numeric($id)){
                $website = webSiteEloquent::find(1);
                $result = ScoreEloquent::where('item_id','=',$id)->get();
                return view('admin.score.index',['result' => $result,'website' => $website]);
            }
        }
    }
    public function student_index(){
        if(Auth::check()){
            $result = ScoreEloquent::where('uid','=',Auth::user()->id)->get();
            $subject = SubjectEloquent::all();
            $website = webSiteEloquent::find(1);
            return view('student.score.index',['result' => $result,'subject' => $subject,'website' => $website]);
        }
    }
}
