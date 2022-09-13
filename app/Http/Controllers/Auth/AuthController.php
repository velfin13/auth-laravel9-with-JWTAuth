<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => "required|min:8",
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = JWTAuth::fromUser($user);

        return response()->json([
            "user" => $user,
            "token" => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credential = $request->only('email', 'password');
        try {

            if (!$token = JWTAuth::attempt($credential)) {
                return response()->json([
                    "error" => "invalid credentials"
                ], 400);
            }
        } catch (JWTException $ex) {
            return response()->json([
                "error" => "password or email invalaid"
            ], 500);
        }

        return response()->json(compact('token'));
    }
}
