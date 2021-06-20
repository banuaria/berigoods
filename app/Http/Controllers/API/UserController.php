<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\User;



class UserController extends BaseController
{
   public function login(Request $request)
   {
       $validator = Validator::make($request->all(),[
           'email' => ['required','string','email'],
           'password' => ['required','string']
       ]);
       if ($validator->fails()){
           return $this->responseError('login failed', 422, $validator->errors());
       }
       if (Auth::attempt(['email'=> $request->email, 'password'=> $request->password])){
           $user = Auth::user();
        //    dd($user);
            $response = [
                'token' => $user->createToken('Tokens')->accessToken,
                'name' => $user->name,
                'email'=> $user->email,
            ];
            return $this->responseOk($response);
       }else{
           return $this->responseError('Wrong email or password', 401);
       }
   }

   public function register(Request $request)
   {
        $validator = Validator::make($request->all(),[
        'name' => ['required','string','max:255'],
        'email' => ['required','string','email','unique:users'],
        'password' => ['required','string','min:8','confirmed'],
        ]);

        if ($validator->fails()){
            return $this->responseError('login failed', 422, $validator->errors());
        }
        $params = [
            'name' => $request->name,
            'email'=> $request->email,
            'role'=> 'customer',
            'password' => Hash::make($request->password),
        ];

        // dd($params);
        if($user = User::create($params)) {
            $token = $user->createToken('Tokens')->accessToken;

            $response = [
                'token' => $token,
                'user' => $user,
            ];
            return $this->responseOk($response);
        }else{
            return $this->responseError('Registration failed',400);
        }
   }

   public function profile(Request $request){
       return $this->responseOk($request->user());
   }
}