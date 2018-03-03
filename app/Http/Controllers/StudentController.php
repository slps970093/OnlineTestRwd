<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;
class StudentController extends Controller
{
    //
    public function index(){
        return View('home/student');
    }
}
