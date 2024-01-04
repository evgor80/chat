<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\FailedRequestException;
use Illuminate\Contracts\Validation\Validator;

class BaseRequest extends FormRequest
{
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
        throw new FailedRequestException('Недопустимые данные.', $validator->errors(), 422);
    }
}