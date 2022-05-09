<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Arr;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    protected $user;
    /**
     * Authenticate User 
     * 
     * @return JSON
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if ($this->attempt($credentials)) {
            $user = $this->user;
            return response()->json([
                'user' =>  new UserResource($user),
                'token' => $user->createToken('User-Token')->plainTextToken], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Invalid Credentials'], Response::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * create a check method for api login
     */
    public function attempt($credentials)
    {
        $email    =  Arr::get($credentials, 'email');
        $password =  Arr::get($credentials, 'password');
        $user     =  User::where('email', $email)->first();
 
        if ($user) {
            $this->user = $user;
            return Hash::check($password, $user->password);
        }
        return false;
    }
}
