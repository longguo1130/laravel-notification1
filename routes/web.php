<?php

use Illuminate\Support\Facades\Route;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DatabaseNotification;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/notify',function(){
    $users = User::all();
    $letter = ['title' => 'Notification','body' => 'This is new !!! '];
    Notification::send($users, new DatabaseNotification($letter));
    return 'notification send';
});
