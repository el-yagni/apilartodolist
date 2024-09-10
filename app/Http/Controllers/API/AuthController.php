<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Validator;




class AuthController extends Controller
{


    public function register(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email|unique:users,email",
            "password" => "required",
            "confirm_password" => "required|same:password"
        ]);


        if ($validators->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ada kesalahan input',
                'data' => $validators->errors()
            ]);
        }



        $input = $request->all();
        if ($input) {
            $input['password'] = bcrypt($input['password']);
            User::create($input)->sendEmailVerificationNotification();
            return response()->json([
                'success' => true,
                'message' => 'Sukses Registrasi, Silahkan lakukan verifikasi email!',
                'status' => 200,
            ], 200);
        }
    }

    public function deleteUser($id)
    {
        $record = User::find($id);


        if (!$record) {
            return response()->json([
                "status" => 404,
                "message" => "data Not Found"
            ]);
        } else {
            $record->delete();
            return response()->json([
                "status" => 200,
                "message" => "data Successfully Deleted"
            ]);

        }

    }

    public function search($id)
    {
        $data = User::find($id);

        if (empty($data)) {
            return response()->json([
                "status" => false,
                "message" => "User Not Found"
            ], 404);
        }


        return response()->json([
            "status" => true,
            "data" => $data
        ], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages(),
                'data' => $validator->errors()
            ], 400);
        }



        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => "Email Or Password invalid!",
            ], 400);
        }


        $tokenUser = $request->user()->createToken('token')->plainTextToken;
        $id = $request->user()->id;
        $name = $request->user()->name;

        return response()->json([
            "status" => true,
            "id" => $id,
            "name" => $name,
            "message" => "Login Successfully",
            "token" => $tokenUser
        ], 200);
    }


    public function verify(Request $request, $id)
    {
        if (!$request->hasValidSignature()) {
            return response()->json([
                "status" => false,
                "message" => "email verifying Fails!"
            ]);
        }

        $user = User::find($id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        return redirect('/success');
    }

    public function notice()
    {
        return response()->json([
            "status" => false,
            "message" => "anda belum melakukan Verifikasi email!"
        ]);
    }

    public function resend()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return response()->json([
                "status" => true,
                "message" => "Email Has been verified"
            ]);
        }

        Auth::user()->sendEmailVerificationNotification();
        return response()->json([
            "status" => true,
            "message" => "Your Resend Request has been send to Gmail"
        ]);
    }


    public function logout(Request $request)
    {
        Auth::user()->tokens->delete();
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "status" => true,
            "message" => "Logout Successfully"
        ], 200);
    }
}
