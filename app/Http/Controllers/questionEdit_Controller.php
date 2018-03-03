<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\question_Back as QuestionEloquent;
use App\question_option as OptionEloquent;
use App\Http\Requests\AnsterReqeust as AnsterReqeust;
use App\question_images as ImageEloquent;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\questionImageRequest as ImageRequest;
use Illuminate\Support\Facades\Gate;
use App\question_Back_type as TypeEloquent;


class questionEdit_Controller extends Controller
{
    //
    public function index($id){
        if(Gate::allows('isAdmin')){
            $result =  QuestionEloquent::orderBy('q_bank_type_id', 'ASC')->where('item_id','=',(int) $id)->get();
            $type = TypeEloquent::get();
            return view('admin.questionEdit.edit')
                    ->with('result',$result)
                    ->with('data_id',$id)
                    ->with('type',$type);
        }
    }
    public function create($id,Request $request){
        $anster_type = null;
        if($request->option >= 2){
            $anster_type = "json";
            $validator = Validator::make($request->all(),[
                'option' => 'required|integer',
                'title' => 'required|string',
                'redioName' => 'required'
            ], [
                'option' => '為必要資料，需為數值',
                'title' => '為必要資料',
                'redioName' => '為必要資料'
            ]);
        }else{
            $anster_type = "string";
            $validator = Validator::make($request->all(),[
                'option' => 'required|integer',
                'title' => 'required|string',
                'anster' => 'required|string'
            ], [
                'option' => '為必要資料，需為數值',
                'title' => '為必要資料',
                'anster' => '為必要資料'
            ]);
        }
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()],400);
        }else{
            //create question data
            $result = QuestionEloquent::create(array(
                'item_id' => (int) $id,
                'q_bank_type_id' => (int) $request->option,
                'question' => htmlspecialchars($request->title),
                'anster_type' => $anster_type,
                'anster' => is_null(htmlspecialchars($request->anster))?null:htmlspecialchars($request->anster)
            ));
            if($result){
                $result = QuestionEloquent::where('question','=',htmlspecialchars($request->title))->first();
                $data_id = (int) $result->id;
                if($request->option >= 2){
                    // create option data
                    $isSuccess = false;
                    if($anster_type == "json"){
                        if(is_array($request->redioName)){
                            for($i = 0;$i<count($request->redioName);$i++){
                                $result = OptionEloquent::create(array(
                                    'question_id' => (int) $data_id,
                                    'option_name' => htmlspecialchars($request->redioName[$i])
                                ));
                                if($result){
                                    $isSuccess = true;
                                }else{
                                    $isSuccess = false;
                                }
                                if(!$isSuccess){
                                    break;
                                } 
                            }
                        }
                    }
                    if($isSuccess){
                        return response()->json(array('status'=>true,'data_id'=> $data_id,'anster_type' => (int) $request->option),201);
                    }
                }
                return response()->json(array('status'=>true,'data_id'=> $data_id,'anster_type' => (int) $request->option),201);
            }
            return response()->json(array('status'=>false),400);
        }
    }
    public function get_question($id){
        $count = QuestionEloquent::find((int) $id)->count();
        if($count >=1){
            $result = QuestionEloquent::find((int) $id);
            $option_data;
            if($result->q_bank_type_id >= 2){
                $tmp = 0;
                $optionResult = OptionEloquent::where('question_id','=',(int) $result->id)->get();
                foreach($optionResult as $option){
                    $option_data[$tmp] = array(
                        'option_name' => htmlspecialchars($option['option_name'])
                    );
                    $tmp++;
                }
            }
            if(isset($option_data)){
                $data = array(
                    'id' => (int) $result->id,
                    'item_id' => (int) $result->item_id,
                    'type' => (int) $result->q_bank_type_id,
                    'question' => htmlspecialchars($result->question),
                    'option' => $option_data
                );
            }else{
                $data = array(
                    'id' => (int) $result->id,
                    'item_id' => (int) $result->item_id,
                    'type' => (int) $result->q_bank_type_id,
                    'question' => htmlspecialchars($result->question),
                    'anster' => $result->anster
                );
            }
            return response()->json($data,200);
        }
        return response()->json(array('status'=> false,'message'=> 'Not Found'),404);        
    }
    public function anster_get($id){
        $result = OptionEloquent::where('question_id','=',(int) $id)->get();
        $data;
        $tmp = 0;
        foreach($result as $row){
            $data[$tmp] = array(
                'id' => (int)$row['id'],
                'option_name' => $row['option_name']
            );
            $tmp++;
        }
        return response()->json($data,200);
    }
    public function anster_action($id,AnsterReqeust $request){
        $result = QuestionEloquent::find((int) $id);
        if(is_array($request->anster)){
            $result->anster = json_encode($request->anster);
            $result->anster_type = "json";
            $result->save();
        }else{
            $result->anster = htmlspecialchars($request->anster);
            $result->anster_type = "string";
            $result->save();
        }
        if($result){
            return response()->json(array('status' => true),201);
        }
        return response()->json(array('status' => false),400);
    }
    public function update($id,Request $request){
        if($request->option >= 2){
            $validator = Validator::make($request->all(),[
                'option' => 'required|integer',
                'title' => 'required|string',
                'redioName' => 'required'
            ], [
                'option' => '為必要資料，需為數值',
                'title' => '為必要資料',
                'redioName' => '為必要資料'
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'option' => 'required|integer',
                'title' => 'required|string',
                'anster' => 'required|string'
            ], [
                'option' => '為必要資料，需為數值',
                'title' => '為必要資料',
                'anster' => '為必要資料'
            ]);
        }
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()],400);
        }else{
            $create_Option = false;
            $isSuccess = false;
            $result = QuestionEloquent::find((int) $id);
            // 單選/複選/是非處理
            switch($request->option){
                case 1:
                    $result->anster_type = "string";
                    $result->anster = $request->anster;
                    $create_Option = false;
                    $isSuccess = true;
                    break;
                case 2:
                    $result->anster_type = "string";
                    $result->anster = null;
                    $create_Option = true;
                    break;
                case 3:
                    $result->anster_type = "json";
                    $result->anster = null;
                    $create_Option = true;
                    break;
            }
            $result->q_bank_type_id = $request->option;
            $result->question = htmlspecialchars($request->title);
            $result->save();
            $result2 = OptionEloquent::where('question_id','=',(int) $id);
            $result2->delete();
            if($create_Option){
                if(is_array($request->redioName)){
                    for($i = 0;$i<count($request->redioName);$i++){
                        $result = OptionEloquent::create(array(
                            'question_id' => (int) $id,
                            'option_name' => htmlspecialchars($request->redioName[$i])
                        ));
                        if(!$result){
                            break;
                            $isSuccess = false;
                        }else{
                            $isSuccess = true;
                        }
                    }
                }
            }
            if($isSuccess){
                switch($request->option){
                    case 1:
                        return response()->json(array('status'=>true),201);
                        break;
                    case 2:
                        return response()->json(array('status'=>true,'data_id'=> $id,'anster_type' => (int) $request->option),201);
                        break;
                    case 3:
                        return response()->json(array('status'=>true,'data_id'=> $id,'anster_type' => (int) $request->option),201);
                        break;
                }
            }
            return response()->json(array('status'=>false),400);
        }
    }
    public function delete($id){
        $result = QuestionEloquent::find((int)$id);
        $qid = $result->q_bank_type_id;
        if(!empty($result->images->file_path)){
            Storage::disk('uploads')->delete($result->images->file_path);
        }
        $result->delete();
        if($qid >= 2){
            $result = OptionEloquent::where('question_id','=',$id);
            $result->delete();
            $result = ImageEloquent::where('question_id','=',$id);
            $result->delete();
        }
        if($result){
            return response()->json(array('status'=>true),201);
        }
        return response()->json(array('status'=>false),400);
    }
    public function image_upload($id,ImageRequest $request){
        $count = ImageEloquent::where('question_id','=',$id)->count();
        if($count == 1){
            $result = ImageEloquent::where('question_id','=',$id)->get();
            foreach($result as $row){
                Storage::disk('uploads')->delete($row['file_path']);
            }
            $result = ImageEloquent::where('question_id','=',$id)->delete();
            if($result){
                if($request->hasFile('file')){
                    $path = Storage::disk('uploads')->putFile('question',$request->file('file'));
                    $filename = explode('question/',$path);
                    $result = ImageEloquent::create(array(
                        'question_id' => (int) $id,
                        'file_path' => $path,
                        'file_name' => $filename[1],
                        'title' => htmlspecialchars($request->title),
                        'content' => htmlspecialchars($request->content)
                    ));
                    if($result){
                        return response()->json(array('status' => true),201);
                    }
                }
            }
        }else{
            if($request->hasFile('file')){
                $path = Storage::disk('uploads')->putFile('question',$request->file('file'));
                $filename = explode('question/',$path);
                $result = ImageEloquent::create(array(
                    'question_id' => (int) $id,
                    'file_path' => $path,
                    'file_name' => $filename[1],
                    'title' => htmlspecialchars($request->title),
                    'content' => htmlspecialchars($request->content)
                ));
                if($result){
                    return response()->json(array('status' => true),201);
                }
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function image_delete($id){
        $result = ImageEloquent::where('question_id','=',$id)->get();
        foreach($result as $row){
            Storage::disk('uploads')->delete($row['file_path']);
        }
        $result = ImageEloquent::where('question_id','=',$id)->delete();
        if($result){
            return response()->json(array('status' => true),201);
        }
        return response()->json(array('status' => false),400);
    }
}
