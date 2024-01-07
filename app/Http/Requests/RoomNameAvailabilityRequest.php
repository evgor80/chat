<?php

namespace App\Http\Requests;

use App\Exceptions\FailedRequestException;
use Illuminate\Contracts\Validation\Validator;

class RoomNameAvailabilityRequest extends BaseRequest
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
            'name' => 'required|string|min:3|unique:rooms',
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
            'name' => '"Название чата"',
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
            'name.required' => 'Необходимо указать название чата для проверки',
            'name.unique' => 'Название уже занято'
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
            $validator->messages()->has('name') &&
            $validator->messages()->get('name')[0] === 'Название уже занято'
        ) {
            throw new FailedRequestException('Название занято', $validator->errors(), 409);
        }
        parent::failedValidation($validator);
    }
}