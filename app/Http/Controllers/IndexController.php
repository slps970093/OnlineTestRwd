<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\test_Subjects as SubjectEloquent;
use App\webSite as webSiteEloquent;
class IndexController extends Controller
{
    public function index(){
        $website = webSiteEloquent::find(1);
        $subject = SubjectEloquent::all();
        return view('index/index',[
            'subject' => $subject,
            'website' => $website
        ]);
    }
}
