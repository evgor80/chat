<?php

namespace Tests\Traits;

use Illuminate\Support\Facades\Validator;

trait MakeValidator
{
    public function makeValidator(array $data)
    {
        $validator = Validator::make($data, $this->request->rules());

        return $validator;
    }
}