<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
class AdminController extends Controller
{
    //
    public function index(){
        if(Auth::user()->competence != 1){
            switch(Auth::user()->competence){
                case 2:
                    return Redirect('student/hello');
                    break;
                default:
                    return Redirect('logout');
                    break;
            }
        }
        return View('home/admin');
    }
}
