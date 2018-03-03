<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Auth;
use Illuminate\Support\Facades\Gate;
class HomeController extends Controller
{
    //
    public function index(){
        if(Auth::check() && Gate::allows('isAdmin')){
            return redirect('admin/website');
        }
        if(Auth::check() && Gate::denies('isAdmin')){
            return redirect('/');
        }
    }
}
