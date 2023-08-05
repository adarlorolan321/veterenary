<?php
 
namespace App\Http\Requests\Auth\Rules;

use App\Models\User\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
 
class ActiveUserRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::whereEmail($value)->first();
        if($user && !$user->hasRole('Org Admin')) {
            if ($user->status == 'inactive') {
                $fail('Your account is suspended. Please contact your Organisations Administrator');
            }
        }
    }
}