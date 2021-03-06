<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ResetPasswordController;

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

Route::get('/', function () {
    if((session()->get('UsersName')))
    {
        
        if(session()->get('role') != '1')
        {
            return redirect('/welcome/'.session("UsersName"));
        }
        else
        {
            return redirect('/dashboard/'.session("UsersName"));
        }
    }
    else
    {
    return redirect('/userlogin');
    }
});
Route::view('/Login','Login');

Route::get('/logout',[UserController::class,"logout"]);
Route::view('/userlogin','userlogin');
Route::get('/welcome/{data}',function ($data) {
    return view('welcome',['name'=>$data]);
});

Route::post("/UserLogin",[UserController::class,"UserLogin"]);

Route::get('/dashboard', function () {
        return view('dashboard');
    });
 
Route::get("/dashboard/users",[UserController::class,"getUsers"]);
Route::get('/connections/{data}',function($data){
    return view('connections',['name'=>$data]);
});

Route::get("/index/{name}/{name2}/{senderId}/{receiverId}/{csrf}",function($name,$name2,$senderId,$receiverId,$csrf){
    return view('index',['name'=>$name,'name2'=>$name2,'senderId'=>$senderId,'receiverId'=>$receiverId,'csrf'=>$csrf]);
});


// Route for mail
Route::get('mail/{email}', [MailController::class,"index"]);

// Route for store msg
Route::get("/storeMessage/{data}",[UserController::class,"storeMessage"]);


// Admin routs

Route::get('/dashboard/{data}',function ($data) {
    return view('admin',['name'=>$data]);
});

Route::get('/Activity',function () { 
    return view('activity_admin',['name'=>session("UsersName")]);
});
Route::get('/Dashboard',function () {
   return view('admin',['name'=>session("UsersName")]);
});

Route::get('export',[AdminController::class, 'export'])->name('student.export');

Route::get('/charts',[AdminController::class,'plotChart']);

Route::get('/chatLogs',[AdminController::class,'chatLogs']);

Route::get('/activities',[AdminController::class,'activities']);

// Use laarvel socialites
// For google authentication

Route::get('/google-redirect', [UserController::class, 'googleRedirect']);
Route::get('/callback', [UserController::class, 'googleCallback']);

// Stripe Payment Gateway


Route::get('stripe', [UserController::class, 'stripe']);
Route::post('stripe', [UserController::class, 'stripePost'])->name('stripe.post');


// ResetPassword
Route::post("/ResetPassword",[ResetPasswordController::class,'index']);
Route::get("/create-new-password/{selector}/{validator}",function($selector,$validator){
    return view('create-new-password',['selector'=>$selector,'validator'=>$validator]);
});
Route::post("/ResetPasswords",[ResetPasswordController::class,'ResetPasswords']);