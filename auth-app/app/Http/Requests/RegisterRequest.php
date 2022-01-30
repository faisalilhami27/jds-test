<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    return [
      'nik' => 'required|max:16|min:16|regex:/^[0-9]*$/',
      'role' => 'required',
      'password' => 'required|max:6|min:6'
    ];
  }

  /**
   * @return array|string[]
   */
  public static function getRules()
  {
    $these = new static;
    return $these->rules();
  }
}
