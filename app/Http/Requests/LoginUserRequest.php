<?php

namespace App\Http\Requests;


class LoginUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:8',
        ];
    }

     /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'username' => '"Имя пользователя"',
            'password' => '"Пароль"',
        ];
    }
}