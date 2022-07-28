<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

$url = 'App\Http\Controllers';

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('email/verify/{id}', 'App\Http\Controllers\VerificationApiController@verify')->name('verificationapi.verify');
Route::post('email/resend', 'App\Http\Controllers\VerificationApiController@resend')->name('verificationapi.resend');
Route::post('/login', $url . '\UserController@login')->name('login');
Route::post('/register', $url . '\UserController@register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'App\Http\Controllers\UserController@logout');
    Route::post('change-password', 'App\Http\Controllers\UserController@changePassword');
    // Route::get('change-password', 'App\Http\Controllers\ChangePasswordController@index');
    // Route::post('change-password', 'App\Http\Controllers\ChangePasswordController@store')->name('change.password');
    Route::resource('/user', 'App\Http\Controllers\UserController');
    Route::resource('/quotes', 'App\Http\Controllers\QuotesController');
    Route::resource('/quiz', 'App\Http\Controllers\QuizController');
    Route::resource('/answer', 'App\Http\Controllers\AnswerOptionController');
    // Route::resource('/pelajaran', 'App\Http\Controllers\LessonController');
    Route::apiResource('/pelajaran', 'App\Http\Controllers\LessonController');
    Route::resource('/bab', 'App\Http\Controllers\ChapterController');
    Route::resource('/progress', 'App\Http\Controllers\ProgressController');
    Route::get('/certificates', 'App\Http\Controllers\CertificateController@exportPDF');
    Route::post('/certificates', 'App\Http\Controllers\CertificateController@store');
    Route::put('/certificates/{id}', 'App\Http\Controllers\CertificateController@update');
});

Route::post('/forgot-password', function (Request $request) {
    $request->validate(["email" => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT ? response()->json(["message" => __($status)], 200) : response()->json(["error" => __($status)], 400);
})->name('password.email');
Route::get('/reset-password/{token}', function ($token) {
    $email = $_GET['email'];
    return view('Auth.reset-password', ['token' => $token, 'email' => $email]);
})->name('password.reset');
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) use ($request) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->save();

            $user->setRememberToken(Str::random(60));

            event(new PasswordReset($user));
        }
    );

    return $status == Password::PASSWORD_RESET ? view("inforeset", ['status' => __($status)]) : view("inforeset", ['status' => __($status)]);
})->name('password.update');
