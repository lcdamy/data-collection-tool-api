<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

/**
 * @group User
 * 
 * APIs for user
 */

class AuthController extends Controller
{
  public $loginAfterSignUp = true;
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
    $user = User::create([
      'firstname' => $request->firstname,
      'lastname' =>  $request->lastname,
      'email' => $request->email,
      'phone' => $request->phone,
      'hospital_id' => $request->hospital_id,
      'password' => bcrypt($request->password),
    ]);

    $token = auth()->login($user);

    return $this->respondWithToken($token);
  }

  public function login(Request $request)
  {
    $credentials = $request->only(['email', 'password']);

    if (!$token = auth()->attempt($credentials)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $this->respondWithToken($token);
  }
  public function getAuthUser(Request $request)
  {
    return response()->json(auth()->user());
  }
  public function logout()
  {
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
  }

  protected function respondWithToken($token)
  {
    return response()->json([
      'access_token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ]);
  }
}
