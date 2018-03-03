<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\User as UserEloquent;
use App\test_Subjects as SubjectsEloquent;
use Auth;
use Redirect;
use View;
use Sociallite;
use App\Http\Requests\AdminCreateRequest as UserRequest;
use Illuminate\Support\Facades\Validator;
use App\student_score as ScoreEloquent;

use App\webSite as webSiteEloquent;

class UserController extends Controller
{
    //
    public function __construct(){
        $this->middleware('guest',['except'=>['getLogout','admin_search',
        'admin_user','create','update','update_password'
        ,'delete','ajax_get_userdata','updateUser','updateUserData','updateUserPassword']]);
    }
    public function admin_user(){
        $result = UserEloquent::paginate(15);
        $website = webSiteEloquent::find(1);
        return view('admin.users.admin')->with('result',$result)->with('website',$website);
    }
    public function admin_search(Request $request){
        if(!empty($request->input('search'))){
            $result = UserEloquent::where('username','like','%'.htmlspecialchars($request->input('search')).'%')->paginate(15);
            $website = webSiteEloquent::find(1);
            return view('admin.users.admin')->with('result',$result)->with('website',$website);
        }
        return redirect('admin/users');
    }
    public function create(UserRequest $request){
        $count = UserEloquent::where('username','=',htmlspecialchars($request->username))->count();
        if($count == 0){
            $result = UserEloquent::create(array(
                'first_name' => htmlspecialchars($request->first_name),
                'last_name' => htmlspecialchars($request->last_name),
                'username' => htmlspecialchars($request->username),
                'password' => Hash::make($request->password),
                'email' => htmlspecialchars($request->email),
                'nickname' => htmlspecialchars($request->nickname),
                'isAdmin' => (int) $request->isAdmin
            ));
            if($result){
                return response()->json(array('status' => true),201);
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function update($id,Request $request){
        if($id != 1){
            $validator = Validator::make($request->all(),array(
                'username' => 'required|max:30',
                'last_name' => 'required|max:5',
                'first_name' => 'required|max:5',
                'nickname' => 'required|max:8',
                'email' => 'required|email',
                'isAdmin'=> 'required|integer'
            ), array(
                'username' => '使用者名稱必須輸入|不得超過30字元',
                'last_name' => '姓氏必須輸入|不得超過5字元',
                'first_name' => '名字必須輸入|不得超過5字元',
                'nickname' => '暱稱必須輸入|不得超過8字元',
                'email' => '信箱必須輸入|輸入資料必須為電子信箱',
                'isAdmin'=> '管理者必須輸入|必須為數值'
            ));
        }else{
            $validator = Validator::make($request->all(),array(
                'username' => 'required|max:30',
                'last_name' => 'required|max:5',
                'first_name' => 'required|max:5',
                'nickname' => 'required|max:8',
                'email' => 'required|email',
            ), array(
                'username' => '使用者名稱必須輸入|不得超過30字元',
                'last_name' => '姓氏必須輸入|不得超過5字元',
                'first_name' => '名字必須輸入|不得超過5字元',
                'nickname' => '暱稱必須輸入|不得超過8字元',
                'email' => '信箱必須輸入|輸入資料必須為電子信箱',
            ));
        }
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()],400);
        }else{
            if(is_numeric($id)){
                $count = UserEloquent::where('username','=',htmlspecialchars($request->username))->count();
                if($count <= 1){
                    if($count = 0){
                        $result = UserEloquent::find($id);
                        $result->first_name = htmlspecialchars($request->first_name);
                        $result->last_name = htmlspecialchars($request->last_name);
                        $result->username = htmlspecialchars($request->username);
                        $result->nickname = htmlspecialchars($request->nickname);
                        $result->email = htmlspecialchars($request->email);
                        if($id != 1){
                            $result->isAdmin = htmlspecialchars($request->isAdmin);
                        }
                        $result->save();
                        if($result){
                            return response()->json(array('status' => true),201);
                        }
                    }else{
                        $result = UserEloquent::where('username','=',htmlspecialchars($request->username))->first();
                        if($result['id'] == $id){
                            $result = UserEloquent::find($id);
                            $result->first_name = htmlspecialchars($request->first_name);
                            $result->last_name = htmlspecialchars($request->last_name);
                            $result->username = htmlspecialchars($request->username);
                            $result->nickname = htmlspecialchars($request->nickname);
                            $result->email = htmlspecialchars($request->email);
                            if($id != 1){
                                $result->isAdmin = htmlspecialchars($request->isAdmin);
                            }
                            $result->save();
                        }else{
                            return response()->json(array('status' => false),400);
                        }
                        if($result){
                            return response()->json(array('status' => true),201);
                        }
                    }
                }
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function update_password($id,Request $request){
        $validator = Validator::make($request->all(),array(
            'password' => 'required|max:30',
        ), array(
            'password' => '密碼必須輸入|不得超過30字元',
        ));
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()],400);
        }else{
            if(is_numeric($id)){
                $result = UserEloquent::find($id);
                $result->password = Hash::make($request->password);
                $result->save();
                if($result){
                    return response()->json(array('status' => true),201);
                }
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function delete($id,Request $request){
        if(is_numeric($id) && $id != 1){
            $result = UserEloquent::find($id)->delete();
            ScoreEloquent::where('uid','=',(int) $id)->delete();
            if($result){
                return response()->json(array('status' => true),201);
            }
        }
        return response()->json(array('status' => false),400);
    }
    public function ajax_get_userdata($id){
        if(is_numeric($id)){
            $result = UserEloquent::find($id);
            return response()->json(array(
                'id' => $result->id,
                'first_name' => $result->first_name,
                'last_name' => $result->last_name,
                'username' => $result->username,
                'nickname' => $result->nickname, 
                'email' => $result->email,
                'isAdmin' => $result->isAdmin
            ),200);
        }
        return response()->json(array('status' => false),400);
    }
    public function getLogin(){
        $website = webSiteEloquent::find(1);
        return view('auth/login/login',['website' => $website]);
    }
    public function postLogin(LoginRequest $request){
        $authData = $request->only(['username','password']);
        //如果有勾選
        if(Auth::attempt($authData,$request->has('rememeber'))){
            return Redirect('home');
        }else{
            return Redirect('login?failed');
        }
    }
    public function getLogout(){
        Auth::logout();
        return Redirect('login?logout');
    }
    public function register(){
        return view('auth/login/register');
    }
    public function postRegister(RegisterRequest $request){
        $count = UserEloquent::where([
            ['username','=',htmlspecialchars($request->username)],
            ['email','=',htmlspecialchars($request->email)]
        ])->count();
        if($count < 1){
            $result= UserEloquent::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'nickname' => $request->nickname,
                'isAdmin' => 1
            ]);
            if($result){
                return response()->json(array('status' => true),201);
            }
        }else{
            return response()->json(array('status' => false,'message' => '帳號或信箱已經被註冊'),400);
        }
        return response()->json(array('status' => false,'message' => 'Register User Failed'),400);
    }
    public function updateUser(){
        $result = UserEloquent::find(Auth::user()->id);
        $website = webSiteEloquent::find(1);
        $subject = SubjectsEloquent::all();
        return view('student.user.index')->with('result',$result)->with('website',$website)->with('subject',$subject);
    }
    public function updateUserData(Request $request){
        $validator = Validator::make($request->all(),array(
            'last_name' => 'required|max:5',
            'first_name' => 'required|max:5',
            'nickname' => 'required|max:8',
            'email' => 'required|email',
        ), array(
            'last_name' => '姓氏必須輸入|不得超過5字元',
            'first_name' => '名字必須輸入|不得超過5字元',
            'nickname' => '暱稱必須輸入|不得超過8字元',
            'email' => '信箱必須輸入|輸入資料必須為電子信箱',
        ));
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()],400);
        }else{
            $result = UserEloquent::find(Auth::user()->id);
            $result->first_name = htmlspecialchars($request->first_name);
            $result->last_name = htmlspecialchars($request->last_name);
            $result->nickname = htmlspecialchars($request->nickname);
            $result->email = htmlspecialchars($request->email);
            $result->save();
            if($result){
                return response()->json(array('status' => true),201);
            }
        }
        return response()->json(['status'=>false],400);
    }
    public function updateUserPassword(Request $request){
        $validator = Validator::make($request->all(),array(
            'password' => 'required|max:30',
            'password2' => 'required|max:30'
        ), array(
            'password' => '密碼必須輸入|不得超過30字元',
            'password2' => '密碼必須輸入|不得超過30字元'
        ));
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()->all()],400);
        }else{
            if($request->password == $request->password2){
                $result = UserEloquent::find(Auth::user()->id);
                $result->password = Hash::make($request->password);
                $result->save();
                if($result){
                    return response()->json(array('status' => true),201);
                }
            }
        }
        return response()->json(['status'=>false],400);
    }
    
}
