<?php

namespace App\Http\Controllers;

use App\HelperMethods\JsonReturn;
use App\Http\Controllers\Controller;
use App\Models\User;
use Firebase\Auth\Token\Exception\InvalidToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use JsonReturn;
    public function register(Request $request)
    {
        $auth = app('firebase.auth');
        $validator = request()->validate([
            'token' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required',
            'is_owner' => 'sometimes|boolean',
        ]);

        //id , phone from credintial
        try {
            $verify = $auth->verifyIdToken($request['token']);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage(),
            ], 401);
        } catch (InvalidToken $e) { // If the token is invalid (expired ...)
            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage(),
            ], 401);
        }

        $uid = $verify->getClaim('sub');
        $phone = $auth->getUser($uid)->phoneNumber;

        if(User::find($uid)){
            return $this->errorJson('this user allready taken');
        }
        $user = User::create(array_merge($validator, ['password' => bcrypt($validator['password']), 'id' => $uid, 'phone' => $phone]));
        $token = Auth::fromUser($user);
        return Response()->json(compact('token'));
    }
}
