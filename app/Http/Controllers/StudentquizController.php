<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student_Fillin as FillinEloquent;
use App\test_Items as ItemsEloquent;
use App\student_score as ScoreEloquent;
use Auth;
use DB;
use App\webSite as webSiteEloquent;
use Illuminate\Support\Facades\Validator;
use Storage;
class StudentquizController extends Controller
{
    // 隨機產生題庫
    public function Random_question($id){
        if(Auth::check()){
            $result = ItemsEloquent::find($id);
            if($result->isRepeat != 0){
                $count = ScoreEloquent::where([
                    ['uid','=',Auth::user()->id],
                    ['item_id','=',(int) $id]
                ])->count();
                if($count >= 1){
                    return response()->json(array('status' => false,'message'=>'系統檢測到你已經完成測驗!'),400);
                }
            }
            $count = FillinEloquent::where('uid','=',Auth::user()->id)->count();
            if($count != 0){
                FillinEloquent::where('uid','=',Auth::user()->id)->delete();
            }
            // 題目隨機出題
            $result = DB::select('SELECT * FROM `question_bank` WHERE item_id = :id ORDER BY RAND()',['id' => (int) $id]);
            foreach($result as $row){
                FillinEloquent::create(array(
                    'uid' => Auth::user()->id,
                    'qb_id' => $row->id,
                    'item_id' => $row->item_id,
                    'q_bank_type_id' => $row->q_bank_type_id,
                    'question' => $row->question,
                    'anster_type' => $row->anster_type,
                    'correct_answer' => $row->anster,
                    'anster' => null
                ));
            }
            return response()->json(array('href' => url('student/quiz')."/".$id),201);
        }
        return response()->json(array('status'=> false),400);
    }
    // 測驗介面
    public function quiz($item_id){
        if(Auth::check()){
            $website = webSiteEloquent::find(1);
            $count = FillinEloquent::where('uid','=',Auth::user()->id)->count();
            $item_data = ItemsEloquent::find((int) $item_id);
            $quiz_data = FillinEloquent::where('uid','=',Auth::user()->id)->paginate(1);
            return view('student.quiz.index',['result' => $quiz_data,'item' => $item_data,'website' => $website,'count' => $count]);
        }
    }   
    public function Fillin_anster(Request $request){
        if(Auth::check()){
            $validator = Validator::make($request->all(), array('qid' => 'required|integer','anster' => 'required'), array('qid' => '必要值|需為數值','anster' => '必要值'));
            if($validator->fails()){
                return response()->json(['error'=>$validator->errors()->all()],400);
            }else{
                if(is_array($request->anster)){
                    $result = FillinEloquent::find($request->qid);
                    $result->anster = json_encode($request->anster);
                    $result->save();
                    if($result){
                        return response()->json(array('status'=> true),201);
                    }
                }else{
                    $result = FillinEloquent::find($request->qid);
                    $result->anster = (int) $request->anster;
                    $result->save();
                    if($result){
                        return response()->json(array('status'=> true),201);
                    }
                }
            }
        }
        return response()->json(array('status'=> false),400);
    }
    public function Score_calc(){
        if(Auth::check()){
            $count = FillinEloquent::where('uid','=',Auth::user()->id)->count();
            $question_score= round(100/$count);
            $Student_score = 0;
            //計算分數
            $result = FillinEloquent::where('uid','=',Auth::user()->id)->get();
            $item_id = null;
            foreach($result as $row){
                if($row->correct_answer == $row->anster){
                    $Student_score += $question_score;
                }
                $item_id = $row->item_id;
            }           
            //寫入分數
            $ScoreResult = ScoreEloquent::where([
                ['uid','=',Auth::user()->id],
                ['item_id','=',(int) $item_id]
            ])->count(); 
            if($ScoreResult != 0){
                $ScoreResult = ScoreEloquent::where([
                    ['uid','=',Auth::user()->id],
                    ['item_id','=',(int) $item_id]
                ])->first();
                if($Student_score > $ScoreResult->score){
                    $ScoreResult->score = $Student_score;
                    $ScoreResult->save();
                    FillinEloquent::where('uid','=',Auth::user()->id)->delete();
                    return response()->json(array('status'=>true,'score' => $Student_score,'href'=>url('student/score')),200);
                }else{
                    FillinEloquent::where('uid','=',Auth::user()->id)->delete();
                    return response()->json(array('status'=>true,'score' => $ScoreResult->score,'href'=>url('student/score')),200);
                }
            }else{
                $ScoreResult = ScoreEloquent::create(array(
                    'uid' => Auth::user()->id,
                    'item_id' => $item_id,
                    'score' => $Student_score
                ));
                if($ScoreResult){
                    FillinEloquent::where('uid','=',Auth::user()->id)->delete();
                    return response()->json(array('status'=>true,'score' => $Student_score,'href'=>url('student/score')),200);
                }
            }
        }
        return response()->json(array('status'=>false,'message'=>'please login website'),400);
    }
}
