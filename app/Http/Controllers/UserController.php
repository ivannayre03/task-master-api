<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request) // users -> POST
    {
        $data = $request->all(); // { name, email, password } Ivan Nayre, ivannayre03@gmail.com, roronoaace12
        $userExist = User::where('email', $request->email)->exists(); // true or false

        if($userExist)
        {
            return response()->json(['message' => "This email is already been taken."], Response::HTTP_FORBIDDEN);
        }

        $data['password'] = Hash::make($request->password);
        User::create($data);

        return $this->login($request);
    }

    public function login(Request $request) // login
    {
        $data = $request->all();
        $authenticated = $this->authenticate($data);

        if($authenticated)
        {
            $user = [
                'name' => $authenticated->name,
                'email' => $authenticated->email,
                'auth_token' => $authenticated->remember_token
            ];

            return response()->json(['message' => "Successfully logged in.", 'user' => $user]);
        }

        return response()->json(['message' => "Invalid email or password."], Response::HTTP_FORBIDDEN);
    }

    public function authenticate($credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if($user)
        {
            if(Hash::check($credentials['password'], $user->password))
            {
                $secretKey = 'task_master_'.date('Y').'_secret_' . $user->email;
                $authToken = $user->createToken($secretKey)->plainTextToken;
                $user->remember_token = $authToken;
                $user->save();    
            }
        }

        return $user;
    }
}
