<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'IndexController@index');

Route::group(['prefix'=>'social'],function(){
    Route::get('google/redirect',[
        'as' => 'social',
        'uses' => 'SocialController@google_redirectToProvider'
    ]);
    Route::get('google/callback',[
        'as' => 'social',
        'uses' => 'SocialController@google_handleProviderCallback'
    ]);
    Route::get('facebook/redirect',[
        'as' => 'social',
        'uses' => 'SocialController@facebook_redirectToProvider'
    ]);
    Route::get('facebook/callback',[
        'as' => 'social',
        'uses' => 'SocialController@facebook_handleProviderCallback'
    ]);
});
Route::group(['middleware' => 'auth'], function() {
    Route::get('home','HomeController@index');
    Route::group(['prefix'=>'admin'],function(){
        Route::get('home',[
            'as' => 'admin',
            'uses' => 'AdminController@index'
        ]);
    });
    Route::group(['prefix'=>'student'],function(){
        Route::get('home',[
            'as' => 'student',
            'uses' => 'StudentController@index'
        ]);
    });
    Route::group(['prefix' => 'admin'], function() {
        Route::get('website','WebsiteController@index');
        Route::patch('website','WebsiteController@update');
        Route::get('users','UserController@admin_user');
        Route::get('users/search','UserController@admin_search');
        Route::get('users/{id}','UserController@ajax_get_userdata');
        Route::post('users','UserController@create');
        Route::patch('users/{id}','UserController@update');
        Route::patch('users/password/{id}','UserController@update_password');
        Route::delete('users/{id}','UserController@delete');
        Route::get('test/subject','testSubjectController@index');
        Route::post('test/subject','testSubjectController@create');
        Route::patch('test/subject/{id}','testSubjectController@update');
        Route::delete('test/subject/{id}','testSubjectController@delete');
        Route::get('ajax/test/subject/','testSubjectController@ajax_read');
        Route::get('ajax/test/subject/{id}','testSubjectController@ajax_read');
        Route::get('test/items','testItemController@index');
        Route::post('test/items','testItemController@create');
        Route::patch('test/items/{id}','testItemController@update');
        Route::delete('test/items/{id}','testItemController@delete');
        Route::get('ajax/test/items/{id}','testItemController@ajax_get_data');
        Route::get('register','UserController@register');
        Route::post('register','UserController@postRegister');
        Route::get('logout','UserController@getLogout');
        Route::get('question/edit/{id}','questionEdit_Controller@index');
        Route::post('question/edit/{id}','questionEdit_Controller@create');
        Route::patch('question/edit/{id}','questionEdit_Controller@update');
        Route::delete('question/edit/{id}','questionEdit_Controller@delete');
        Route::get('question/{id}','questionEdit_Controller@get_question');
        Route::get('question/anster/{id}','questionEdit_Controller@anster_get');
        Route::post('question/image/{id}','questionEdit_Controller@image_upload');
        Route::delete('question/image/{id}','questionEdit_Controller@image_delete');
        Route::patch('question/anster/{id}','questionEdit_Controller@anster_action');
        Route::get('score/{id}','StudentScoreController@admin_index');
    });
    Route::group(['prefix' => 'student'], function() {
        Route::get('items/{id}','testItemController@student_items');
        Route::get('ajax/items/{id}','testItemController@student_get_item');
        Route::get('question/rand/{id}','StudentquizController@Random_question');
        Route::get('quiz/{id}','StudentquizController@quiz');
        Route::patch('quiz','StudentquizController@Fillin_anster');
        Route::get('count','StudentquizController@Score_calc');
        Route::get('score','StudentScoreController@student_index'); 
        Route::get('user','UserController@updateUser');
        Route::patch('user','UserController@updateUserData');
        Route::patch('user/passsword','UserController@updateUserPassword');
    });
});
Route::get('login','UserController@getLogin');
Route::post('login','UserController@postLogin');
Route::post('register','UserController@postRegister');
Route::get('logout','UserController@getLogout');


Route::get('failed',function(){
    echo "failed! ";
});