<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'role_id' => ['required', new Enum(RoleEnum::class)],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['nullable'],
            'address' => ['nullable', 'string'],
            'job_title' => ['nullable'],
            'password' => [
                Password::min(8)->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed',
                'nullable'
            ]

        ];
    }
}
