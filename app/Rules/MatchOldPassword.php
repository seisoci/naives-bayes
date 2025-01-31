<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class MatchOldPassword implements Rule
{
  public function passes($attribute, $value)
  {
    return Hash::check($value, auth()->user()->password);
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    return 'The :attribute is match with old password.';
  }
}
