<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controllers;
use App\Models\User;
use illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;

class UserController extends Controller
{
    use verifiesEmails;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $role = $request->role;
        if ($role === 'teacher') {
            $user = User::with('lesson')
                ->where('role', '=', $role)->get();
        } else {
            $user = User::with('lesson', 'certificates', 'progress')
                ->where('role', '=', $role)->get();
        }
        return $user;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->first();
        if ($user) {
            return response()->json([
                'success' => $user,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada detail data!',
                'data' => 'Kosong!'
            ], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $user->role = $request->role;
        $user->name = $request->name;
        // $user -> email = $request -> email;

        if ($user->update()) {
            // $user->sendApiEmailVerificationNotification();
            // $success['message'] = 'Tolong konfirmasi email kamu di mail box!';
            $success['token'] =  $user->createToken('nApp')->accessToken;
            $success['name'] =  $user->name;
            return response()->json([
                'token' => $success,
                'message' => 'Berhasil update data',
                'user' => $user
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus!',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data gagal dihapus!',
            ], 401);
        }
    }

    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('nApp')->accessToken;
            $success['message'] = 'Login successfull';
            $success['message'] = 'Login successfull';
            return response()->json([
                'token' => $success,
                'message' => 'Berhasil login',
                'user' => $user
            ], 200);
        } else {
            return response()->json([
                'message' => 'Email dan Password salah'
            ], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => "Email sudah terdaftar silahkan login"
            ], 500);
        } else {
            $input = $request->all();
            $input['role'] = $request->role;
            $input['name'] = $request->name;
            $input['email'] = $request->email;
            $input['password'] = bcrypt($input['password']);
            $user = User::create($input);
            //   $user->sendApiEmailVerificationNotification();
            //   $success['message'] = 'Tolong konfirmasi email kamu di mail box!';
            $success['token'] =  $user->createToken('nApp')->accessToken;
            $success['name'] =  $user->name;
            return response()->json([
                'token' => $success,
                'message' => 'Berhasil mendaftar',
                'user' => $user
            ], 200);
        }
    }

    public function changePassword(Request $request)
    {
        $input = $request->all();
        $userid = Auth::guard('api')->user()->id;
        $rules = array(
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        );
        $validator = Validator::make($input, $rules);
        if ($validator->fails()) {
            return response()->json([
                "message" => "Kata sandi baru dan konfirmasi kata sandi harus samaaaa",
                "data" => array()
            ], 422);
        } else {
            try {
                if ((Hash::check(request('old_password'), Auth::user()->password)) == false) {
                    return response()->json([
                        "message" => "Kata sandi lama anda salah",
                        "data" => array()
                    ], 401);
                } else if ((Hash::check(request('new_password'), Auth::user()->password)) == true) {
                    return response()->json([
                        "message" => "Kata sandi lama dan baru tidak boleh sama",
                        "data" => array()
                    ], 226);
                } else {
                    User::where('id', $userid)->update(['password' => Hash::make($input['new_password'])]);
                    return response()->json([
                        "message" => "Berhasil mengganti kata sandi",
                        "data" => array()
                    ], 200);
                }
            } catch (\Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }
                return response()->json([
                    "message" => $msg,
                    "data" => array()
                ], 400);
            }
        }
        return \Response::json($arr);
    }

    public function logout(Request $request)
    {
        $logout = $request->user()->token()->revoke();
        if ($logout) {
            return response()->json([
                'message' => 'Berhasil logout!'
            ]);
        } else {
            return response()->json([
                'message' => 'Gagal logout!'
            ]);
        }
    }
}
