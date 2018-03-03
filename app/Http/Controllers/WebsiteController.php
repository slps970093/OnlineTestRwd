<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\webSite as webSiteEloquent;
use Auth;
use Illuminate\Support\Facades\Gate;
class WebsiteController extends Controller
{
    public function index(){
        if(Auth::check() && Gate::allows('isAdmin')){
            $result = webSiteEloquent::find(1);
            return view('admin.website.index',['result' => $result,'website' => $result]);
        }
    }
    public function update(Request $request){
        if(Auth::check() && Gate::allows('isAdmin')){
            $result = webSiteEloquent::find(1);
            $result->website_name = htmlspecialchars($request->site_name);
            $result->description = htmlspecialchars($request->description);
            $result->keyword = htmlspecialchars($request->keyword);
            $result->save();
            if($result){
                return response()->json(array('status'=>true),201);
            }else{
                return response()->json(array('status'=>false),201);
            }
        }
    }
}
