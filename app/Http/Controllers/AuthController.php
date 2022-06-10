<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use App\Http\Resources\RegisterResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
protected User $user;

public function __construct(User $user)
{
        $this->user = $user;
      
}
    
public function register(User $user, RegisterRequest $request):RegisterResource
    {    
        $validatedData = $request->validated();
        
        $user = User::create([
                            'name' => $validatedData['name'],
                           'email' => $validatedData['email'],
                           'password' => Hash::make($validatedData['password']),
                        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return (new RegisterResource($user))              
               ->additional(['status' =>"true",
               "statusCode"=>200, 
               'access_token' => $token,
               'token_type' => 'Bearer'
            ]);
       
}
public function login(User $user, LoginRequest $request):JsonResponse
{
    if (!Auth::attempt($request->validated())) {
        return response()->json([
        'status' =>"true",
        "statusCode"=>401, 
        'message' => 'Invalid login details'
                ], 401);
            }

    $user = User::where('email', $request['email'])->firstOrFail();

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'status' =>"true",
        "statusCode"=>200, 
        'user'=>$user,
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
}
}
