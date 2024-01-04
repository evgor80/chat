<?php

namespace App\Http\Requests;
use App\Exceptions\FailedRequestException;
use Illuminate\Contracts\Validation\Validator;


class StoreUserRequest extends BaseRequest
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
            'username' => 'required|string|min:3|unique:users',
            'password' => 'required|string|min:8',
            'confirm_password' => 'required|string|same:password',
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
            'confirm_password' => '"Подтвердить пароль"'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'username.unique' => 'Имя занято',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \App\Exceptions\FailedRequestException
     */
    
    protected function failedValidation(Validator $validator)
    {
        if (
            $validator->messages()->has('username') &&
            $validator->messages()->get('username')[0] === 'Имя занято'
        ) {
            throw new FailedRequestException('Имя занято', $validator->errors(), 409);
        }
        parent::failedValidation($validator);
    }
}
