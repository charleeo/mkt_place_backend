<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\EmailValidationRule;
use Modules\User\Rules\PasswordValidationRule;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "username" => [
                'required', "string", "min:2", "max:225", "unique:users,username"
            ],
            "email" => [
                'required', "unique:users,email", new EmailValidationRule
            ],
            "password" => [
                'required', new PasswordValidationRule
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
