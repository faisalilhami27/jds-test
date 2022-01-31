<?php

use Illuminate\Support\Facades\Validator;

if (!function_exists('rules')) {
  function rules($request, $rules, $messages = [], $attributes = [])
  {
    return Validator::make($request, $rules, $messages, $attributes);
  }
}
