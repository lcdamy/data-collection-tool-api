<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Hospital;

/**
 * @group User
 * 
 * APIs for user
 */

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'register']]);
  }

  /**
   * Register a user
   * 
   * 
   * @response {
   *  "access_token": null,
   * "token_type": "bearer",
   * "expires_in": 3600
   * }
   */
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'firstname' => 'required|string',
      'lastname' => 'required|string',
      'email' => 'required|email|unique:users',
      'password' => 'required|confirmed|min:6',
      'phone' => 'required|unique:users'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 422);
    }

    $user = User::create(array_merge($validator->validated(), ['password' => bcrypt($request->password)]));
    return response()->json([
      'message' => 'User created successfully',
      'user' => $user
    ]);
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => 'required|email',
      'password' => 'required|string|min:6'
    ]);
    if ($validator->fails()) {
      return response()->json($validator->errors(), 400);
    }
    $token_validity = 24 * 60;
    $this->guard()->factory()->setTTL($token_validity);

    if (!$token = $this->guard()->attempt($validator->validate())) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }
    return $this->respondWithToken($token);
  }

  public function logout()
  {
    $this->guard()->logout();
    return response()->json(['message' => 'Successfully logged out']);
  }

  public function refresh()
  {
    return response()->json($this->respondWithToken($this->guard()->refresh()));
  }

  public function getAuthUser()
  {
    return response()->json($this->guard()->user());
  }

  protected function guard()
  {
    return Auth::guard();
  }

  protected function respondWithToken($token)
  {
    return response()->json([
      'token' => $token,
      'token_type' => 'bearer',
      'token_validity' => $this->guard()->factory()->getTTL() * 60,
      'authenticated_user' => $this->guard()->user()
    ]);
  }
}
