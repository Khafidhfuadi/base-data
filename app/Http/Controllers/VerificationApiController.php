<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Verified;

class VerificationApiController extends Controller
{
  use VerifiesEmails;
  /**
  * Show the email verification notice.
  *
  */
  public function show()
  {
    //
  }
  /**
  * Mark the authenticated user's email address as verified.
  *
  * @param \Illuminate\Http\Request $request
  * @return \Illuminate\Http\Response
  */
  public function verify(Request $request) {
    $userID = $request['id'];
    $user = User::findOrFail($userID);
    $date = date('Y-m-d g:i:s');
    $user->email_verified_at = $date;
    $user->save();
    // return response()->json('Email telah di verifikasi!');
    return view('emailverified');
  }
  /**
  * Resend the email verification notification.
  *
  * @param \Illuminate\Http\Request $request
  * @return \Illuminate\Http\Response
  */
  public function resend(Request $request)
  {
    if ($request->user()->hasVerifiedEmail()) {
    return response()->json('Anda sudah memiliki email terverifikasi!!!', 422);
    // return redirect($this->redirectPath());
  }
    $request->user()->sendEmailVerificationNotification();
    return response()->json('Notifikasi telah dikirim kembali');
    // return back()->with('resent', true);
  }
}