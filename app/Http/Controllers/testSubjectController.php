<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\testSubjectRequest as testSubjectRequest;
use App\test_Subjects as testSubjectsEloquent;
use App\test_Items as ItemEloquent;
use Redirect;
use Illuminate\Support\Facades\Gate;
use App\webSite as webSiteEloquent;
class testSubjectController extends Controller
{
    public function index(){
        if(Gate::allows('isAdmin')){
            $result = testSubjectsEloquent::paginate(10);
            $website = webSiteEloquent::find(1);
            return view('admin.testSubject.index',['result' => $result,'website' =>$website]);
        }
    }
    public function create(testSubjectRequest $request){
        $count = testSubjectsEloquent::where('subjects_name','=',$request->subject_name)->count();
        if($count <= 0){
            $result = testSubjectsEloquent::create(array(
                'subjects_name' => htmlspecialchars($request->subject_name)    
            ));
            if($result){
                return response()->json(array('status' => 'true'),201);
            }else{
                return response()->json(array('status' => 'false'),400);
            }
        }
        return response()->json(array('status' => 'false'),400);

    }
    public function update(testSubjectRequest $request,$id){
        $count = testSubjectsEloquent::where('subjects_name','=',$request->subject_name)->count();
        if($count <= 1){
            if(is_numeric($id)){
                $result = testSubjectsEloquent::find($id);
                $result->subjects_name = htmlspecialchars($request->subject_name);
                $result->save();
                if($result){
                    return response()->json(array('status' => 'true'),201);
                }
            }
        }
        return response()->json(array('status' => 'false'),400);
    }
    public function delete(Request $request,$id){
        if(is_numeric($id)){
            $result = testSubjectsEloquent::find($id);
            $result->delete();
            $result = ItemEloquent::where('test_create_id','=',$id);
            $result->delete();
            if($result){
                return response()->json(array('status' => 'true'),201);
            }
        }
        return response()->json(array('status' => 'false'),400);
    }
    public function ajax_read($id = null){
        $arr;
        if(is_numeric($id) && !is_null($id)){
            $result = testSubjectsEloquent::find($id);
            $arr = array(
                'id' => $result->id,
                'name' => $result->subjects_name
            );
        }else{
            $result = testSubjectsEloquent::all();
            $tmp = 0;
            foreach($result as $row){
                $arr[$tmp]['id'] = $row->id;
                $arr[$tmp]['name'] = $row->subjects_name;
                $tmp++;
            }
        }
        return response()->json($arr,200);
    }
}
