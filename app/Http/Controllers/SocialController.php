<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use App\User as UserEloquent;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Hash;
use View;
use Socialite;

class SocialController extends Controller
{
    //
    /**
     * Redirect the user to the Google authentication page.echo "hello";
     *
     * @return Response
     */
     public function google_redirectToProvider()
     {
         return Socialite::driver('google')->redirect();
     }
 
     /**
      * Obtain the user information from Google.
      *
      * @return Response
      */
     public function google_handleProviderCallback()
     {
         $user = Socialite::driver('google')->user();
         //dd($user);
         // $user->token;
         $social_user = UserEloquent::where('email', $user->email)->first();
         $login_user = NULL;
         if(!empty($social_user)){
            echo "hello";
            $social_user = UserEloquent::where('email',$user->email)->first();
            $login_user = $social_user;
            //綁定帳戶
            $social_user->google_uid = $user->id;
            $social_user->save();
         }else{
            $new_user = UserEloquent::create([
                'first_name' => $user->name,
                'last_name' => $user->name,
                'username' => $user->email,
                'email' => $user->email,
                'password' => Hash::make(str_random(30)),
                'nickname' => $user->name,
                'google_uid' => $user->id,
                'isAdmin' => 1
            ]);
            $login_user = $new_user;
         } 
         if(!is_null($login_user)){
            echo "success"; 
            //dd($login_user);
             Auth::login($login_user);
             return Redirect('home');
         }
         return App::about(500);
     }
    /**
     * Redirect the user to the Facebook authentication page.
     *
     * @return Response
     */
     public function facebook_redirectToProvider()
     {
         return Socialite::driver('facebook')->redirect();
     }
 
     /**
      * Obtain the user information from Facebook.
      *
      * @return Response
      */
     public function facebook_handleProviderCallback()
     {
         $user = Socialite::driver('facebook')->user();
         //dd($user);
         // $user->token;
         
         $social_user = UserEloquent::where('email', $user->email)->first();
         $login_user = NULL;
         if(!empty($social_user)){
            $social_user = UserEloquent::where(['email' => $user->email,'facebook_uid' => $user->id])->first();
            if(!empty($social_user)){
                $social_user = UserEloquent::where('email',$user->email)->first();
                $login_user = $social_user;
                //綁定帳戶
                $social_user = UserEloquent::where('email',$user->email)->first();
                $social_user->facebook_uid = $user->id;
                $social_user->save();
            }
         }else{
            $new_user = UserEloquent::create([
                'first_name' => $user->name,
                'last_name' => $user->name,
                'username' => $user->email,
                'email' => $user->email,
                'password' => Hash::make(str_random(30)),
                'nickname' => $user->name,
                'facebook_uid' => $user->id,
                'isAdmin' => 1
            ]);
            $login_user = $new_user;
         } 
         if(!is_null($login_user)){
             Auth::login($login_user);
             return Redirect('home');
         }
         return App::about(500);
     }
     public function firstSocialconfig(){
        // 初次使用社群登入者，進行用戶初始化設定
    }
}
