<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
  /**
   * register user
   * @param Request $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function register(Request $request)
  {
    try {
      $data = $request->all();
      $validator = Validator::make($data, [
        'nik' => 'required|max:16|min:16|regex:/^[0-9]*$/',
        'role' => 'required',
        'password' => 'required|max:6|min:6'
      ]);

      /* check validation */
      if ($validator->fails()) {
        return ResponseFormatter::error(null, $validator->errors());
      }

      $data['password'] = Hash::make($data['password']);
      $user = User::create($data);
      return ResponseFormatter::success($user, 'Success to create account');
    } catch (\Exception $e) {
      return ResponseFormatter::error(null, $e->getMessage(), $e->getCode());
    }
  }
}
