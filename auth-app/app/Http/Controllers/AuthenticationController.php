<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationController extends Controller
{
  /**
   * Create a new AuthController instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }

  /**
   * Get a JWT via given credentials.
   *
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request)
  {
    try {
      $credentials = $request->only('nik', 'password');
      $token = JWTAuth::attempt($credentials);
      if (!$token) {
        return ResponseFormatter::error(null, 'NIK or password is wrong');
      }
    } catch (JWTException $e) {
      return ResponseFormatter::error(null, 'Token failed to create', 500);
    }
    $user = User::where('id', Auth::id())->first();
    return $this->respondWithToken($user, $token);
  }

  /**
   * Log the user out (Invalidate the token).
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout()
  {
    Auth::logout();
    return ResponseFormatter::success(null, 'Logout success');
  }

  /**
   * Get the token array structure.
   *
   * @param $data
   * @param $token
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function respondWithToken($data, $token)
  {
    $data = [
      'data' => $data,
      'token' => $token,
      'token_type' => 'bearer',
      'expires_in' => auth('api')->factory()->getTTL() * 60
    ];

    return ResponseFormatter::success($data, 'Success login to application');
  }

  /**
   * get authenticated user
   * @return mixed
   */
  public function getAuthenticatedUser()
  {
    try {
      DB::beginTransaction();
      $user = JWTAuth::parseToken()->authenticate();
      $data = User::where('id', $user->id)->first();
      if (is_null($data)) {
        return ResponseFormatter::error(null, 'User not found.', 404);
      }
      DB::commit();
      return ResponseFormatter::success($data, 'Success to get user data');
    } catch (Throwable $th) {
      return ResponseFormatter::error(null, $th->getMessage(), $th->getCode());
    }
  }
}
