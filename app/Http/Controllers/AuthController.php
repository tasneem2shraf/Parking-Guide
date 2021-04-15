<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use JsonReturn;
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login()
    {
        $val = request()->validate([
            'is_owner' => 'required|bool'
        ]);

        $credentials = request(['phone', 'password']);
        if (!$token = Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if(Auth::user()->is_owner || (Auth::user()->is_owner == $val['is_owner'])){

            return $this->respondWithToken($token);
        }else{
            return $this->errorJson('You not have a permision');
        }
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ]);
    }
}
