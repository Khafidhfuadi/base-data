<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/certif', function () {
    return view('Certificates/certif');
});

Route::get('/pdf/{user_id}/{lesson_id}', 'App\Http\Controllers\CertificateController@exportPDF');
