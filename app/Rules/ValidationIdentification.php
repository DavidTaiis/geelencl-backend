<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class ValidationIdentification implements Rule
{
    

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
    
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::where('identification_card', $value)
            ->first();
        if ($user != null) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El usuario ya se encuentra registrado!';
    }
}
