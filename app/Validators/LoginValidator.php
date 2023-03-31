<?php

namespace App\Validators;

use App\Rules\ValidationPassword;
use App\Rules\VerificationAssignCompany;
use App\Rules\validationRole;
use Illuminate\Validation\Rule;

/**
 * Class LoginValidator
 * @package App\Validators
 */
class LoginValidator extends BaseValidator
{

    public function email($input)
    {
        $messages = [
            'email.exists'  => 'El usuario no existe o se encuentra inactivo'
        ];
        $rule = [
            'email' => [
                'required',
                Rule::exists('users', 'email')
                ->where('status' , 'ACTIVE')
            ],
        ];
        $this->validate($input, $rule, $messages);

    }
    public function password($input)
    {
        $rule = [
            'password' => [
                'required',
                new ValidationPassword($input['email']),
            ],
        ];

        $this->validate($input, $rule);

    }
    public function role($user,$input)
    {
        $rule = [
            'role' => [
                'required',
                new validationRole($user),
            ],
        ];

        $this->validate($input, $rule);

    }

}
