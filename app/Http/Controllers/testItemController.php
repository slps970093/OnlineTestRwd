<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\testItemRequest as ItemRequest;
use App\test_Items as ItemEloquent;
use App\test_Subjects as SubjectEloquent;
use Illuminate\Support\Facades\Gate;
use App\question_Back as QuestionBackEloquent;
use App\question_option as OptionEloquent;
use App\question_images as ImagesEloquent;
use App\student_score as ScoreEloquent;
use App\webSite as webSiteEloquent;
use Auth;
use Storage;
class testItemController extends Controller{
    public function index(){
        if(Gate::allows('isAdmin')){
            $result = ItemEloquent::paginate(10);
            $website = webSiteEloquent::find(1);
            return view('admin.testItem.index',['result' => $result,'website' =>$website]);
        }
    }
    public function create(ItemRequest $request){
        // check test Subject is exist
        $count = SubjectEloquent::where('id','=',$request->test_create_id)->count();
        if($count != 0){
            // check item name isn't repeat
            $count = ItemEloquent::where('item_name','=',htmlspecialchars($request->item_name))->count();
            if($count == 0){
                $result = ItemEloquent::create(array(
                    'test_create_id' => htmlspecialchars($request->test_create_id),
                    'item_name' => htmlspecialchars($request->item_name),
                    'content' => htmlspecialchars($request->content),
                    'isRepeat' => (int) $request->isRepeat
                ));
                if($result){
                    return response()->json(array('status' => true),200);
                }
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function update(ItemRequest $request,$id){
        //check test Subject is exist
        $count = SubjectEloquent::where('id','=',$request->test_create_id)->count();
        if($count != 0 && is_numeric($id)){
            $count = ItemEloquent::where('item_name','=',htmlspecialchars($request->item_name))->count();
            if($count <= 1){
                $result = ItemEloquent::find($id);
                $result->item_name = htmlspecialchars($request->item_name);
                $result->test_create_id = htmlspecialchars($request->test_create_id);
                $result->content = htmlspecialchars($request->content);
                $result->isRepeat = $request->isRepeat;
                $result->save();
                if($result){
                    return response()->json(array('status' => true),201);
                }
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function delete($id){
        if(is_numeric($id)){
            $result = QuestionBackEloquent::where('item_id','=',$id)->get();
            foreach($result as $row){
                if(!empty($row->images->file_path)){
                    Storage::disk('uploads')->delete($row->images->file_path);
                }
                OptionEloquent::where('question_id','=',$row->id)->delete();
                ImagesEloquent::where('question_id','=',$row->id)->delete();
                ScoreEloquent::where('item_id','=',$id)->delete();
            }
            QuestionBackEloquent::where('item_id','=',$id)->delete();
            $result = ItemEloquent::find($id);
            $result->delete();
            if($result){
                return response()->json(array('status' => true),201);
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function ajax_get_data($id = NULL){
        if(!is_null($id) && is_numeric($id)){
            $result = ItemEloquent::find($id);
            $data = array(
                'id' => $result->id,
                'create_id' => $result->test_create_id,
                'create_name' => $result->test_Subject->subjects_name,
                'content'=> $result->content,
                'isRepeat' => $result->isRepeat,
                'item_name' => $result->item_name
            );
            return response()->json($data,200);
        }
    }
    public function student_items($id){
        $subject = SubjectEloquent::all();
        $website = webSiteEloquent::find(1);
        $student = ScoreEloquent::where('uid','=',Auth::user()->id)->get();
        $result = ItemEloquent::where('test_create_id','=',(int) $id)->get();
        return view('student.items.index',[
            'items_result' => $result,
            'subject' => $subject,
            'website' => $website,
            'student' => $student
        ]);
    }
    public function student_get_item($id){
        $result = ItemEloquent::find((int) $id);
        return response()->json(array(
            'item_name' => $result->test_Subject->subjects_name,
            'content' => $result->content,
        ),200);
    }
}
