<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'email'     => 'required|email',
                'password'  => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)
                        ->first();

        if(!$user || !Hash::check($request->password, $user->password))
        {
            return response()->json([
                    'success'       => false,
                    'message'       => 'Login Failed!'
            ]);
        }

        return response()->json([
                'success'       => true,
                'message'       => 'Login Success!',
                'data'          => $user,
                'token'         => $user->createToken('authToken')->accessToken
        ]);
    }

    public function logout(Request $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if($removeToken)
        {
            return response()->json([
                    'success'       => true,
                    'Messages'      => 'Logout Success'
            ]);
        }
    }
}
