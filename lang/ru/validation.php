<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Необходимо принять поле :attribute.',
    'accepted_if' => 'Необходимо принять поле :attribute, если поле :other имеет значение :value.',
    'active_url' => 'Значение поля :attribute должно быть URL-адресом.',
    'after' => 'Значение поля :attribute должно быть датой позже :date.',
    'after_or_equal' => 'Значение поля :attribute должно быть датой, равной или позже :date.',
    'alpha' => 'Поле :attribute должно содержать только буквы.',
    'alpha_dash' => 'Поле :attribute должно содержать только буквы, цифры, тире и подчеркивания.',
    'alpha_num' => 'Поле :attribute должно содержать только буквы и цифры.',
    'array' => 'Поле :attribute должно быть массивом.',
    'ascii' => 'Поле :attribute должно содержать только однобайтовые буквенно-цифровые знаки и символы.',
    'before' => 'Значение поля :attribute должно быть датой до :date.',
    'before_or_equal' => 'Значение поля :attribute должно быть датой, равной или ранее :date.',
    'between' => [
        'array' => 'Поле :attribute должно содержать от :min до :max элементов.',
        'file' => 'Поле :attribute должно быть от :min до :max КБ.',
        'numeric' => 'Значение поля :attribute должно быть от :min до :max.',
        'string' => 'Значение поля :attribute должно содрежать от :min до :max символов.',
    ],
    'boolean' => 'Поле :attribute должно иметь значение true или false.',
    'confirmed' => 'Поле :attribute не совпадает с полем подтверждения.',
    'current_password' => 'Неверный пароль.',
    'date' => 'Значение поля :attribute должно быть допустимой датой.',
    'date_equals' => 'Значение поля :attribute должно быть датой, равной :date.',
    'date_format' => 'Значение поля :attribute должно соответствовать формату :format.',
    'decimal' => 'Значение поля :attribute должно иметь :decimal знак. после запятов.',
    'declined' => 'Поле :attribute должно быть отклонено.',
    'declined_if' => 'Поле :attribute должно быть отклонено, если поле :other имеет значение :value.',
    'different' => 'Значения полей :attribute и :other не должны совпадать.',
    'digits' => 'Значение поля :attribute должно содержать :digits цифр.',
    'digits_between' => 'Значение поля :attribute должно содержать от :min до :max цифр.',
    'dimensions' => 'Поле :attribute содержит изображение с недопустиыми размерами.',
    'distinct' => 'Поле :attribute содержит повторяющееся значение.',
    'doesnt_end_with' => 'Значение поля :attribute не должно оканчиваться одним из следующих значений: :values.',
    'doesnt_start_with' => 'Значение поля :attribute не должно начинаться одним из следующих значений: :values.',
    'email' => 'Поле :attribute должно содержать адрес эл. почты.',
    'ends_with' => 'Значение поля :attribute должно оканчиваться одним из следующих значений: :values.',
    'enum' => 'Выбранное :attribute недопустимо.',
    'exists' => 'Выбранное :attribute недопустимо.',
    'file' => 'Поле :attribute должно содержать файл.',
    'filled' => 'Поле :attribute должно содержать значение.',
    'gt' => [
        'array' => 'Поле :attribute должно содержать больше :value элемент.',
        'file' => 'Поле :attribute должно содержать файл больше :value КБ.',
        'numeric' => 'Значение поля :attribute должно быть больше :value.',
        'string' => 'Значение поля :attribute должно содержать больше :value символ.',
    ],
    'gte' => [
        'array' => 'Поле :attribute должно содержать :value элемент. или больше.',
        'file' => 'Поле :attribute должно содержать файл размером :value КБ или больше.',
        'numeric' => 'Значение поля :attribute должно быть равно :value или больше.',
        'string' => 'Значение поля :attribute должно содержать :value символ. или больше',
    ],
    'image' => 'Поле :attribute должно содержать изображение.',
    'in' => 'Выбранное значение :attribute недопустимо.',
    'in_array' => 'Значение поля :attribute должно существовать в :other.',
    'integer' => 'Значение поля :attribute должно быть целым числом.',
    'ip' => 'Значение поля :attribute должно быть IP-адресом.',
    'ipv4' => 'Значение поля :attribute должно быть IPv4-адресом.',
    'ipv6' => 'Значение поля :attribute должно быть IPv6-адресом.',
    'json' => 'Значение поля :attribute должно быть JSON-строкой.',
    'lowercase' => 'Значение поля :attribute должно содержать только строчные символы.',
    'lt' => [
        'array' => 'Поле :attribute должно содержать меньше :value элемент',
        'file' => 'Поле :attribute должно содержать файл меньше :value КБ.',
        'numeric' => 'Значение поля :attribute должно быть меньше :value.',
        'string' => 'Значение поля :attribute должно содержать меньше :value символ.',
    ],
    'lte' => [
        'array' => 'Поле :attribute должно содержать не больше :value элемент.',
        'file' => 'Поле :attribute должно содержать файл размером :value КБ или меньше.',
        'numeric' => 'Значение поля :attribute должно быть равно :value или меньше.',
        'string' => 'Значение поля :attribute должно содержать :value символ. или меньше.',
    ],
    'mac_address' => 'Значение поля :attribute должно быть MAC-адресом.',
    'max' => [
        'array' => 'Поле :attribute должно содержать не больше :max элемент.',
        'file' => 'Поле :attribute должно содержать файл размером не больше :max КБ.',
        'numeric' => 'Значение поля :attribute должно быть не больше :max.',
        'string' => 'Значение поля :attribute должно содержать не более :value символ.',
    ],
    'max_digits' => 'Значение поля :attribute должно содержать не более :max цифр.',
    'mimes' => 'Поле :attribute должно содержать файл типа: :values.',
    'mimetypes' => 'Поле :attribute должно содержать файл типа: :values.',
    'min' => [
        'array' => 'Поле :attribute должно содержать минимум :min элемент.',
        'file' => 'Поле :attribute должно содержать файл размером минимум :min КБ.',
        'numeric' => 'Значение поля :attribute должно быть минимум :min.',
        'string' => 'Значение поля :attribute должно содержать минимум :min символ.',
    ],
    'min_digits' => 'The :attribute field must have at least :min digits.',
    'missing' => 'Поле :attribute должно быть пустым.',
    'missing_if' => 'Поле :attribute должно быть пустым, если поле :other имеет значение :value.',
    'missing_unless' => 'Поле :attribute должно быть пустым, если только поле :other не имеет значение :value.',
    'missing_with' => 'Поле :attribute должно быть пустым, если заполнены какие-либо из полей :values.',
    'missing_with_all' => 'Поле :attribute должно быть пустым, если заполнены все поля :values.',
    'multiple_of' => 'Значение поля :attribute должно быть кратно :value.',
    'not_in' => 'Выбрано недопустимое значение :attribute.',
    'not_regex' => 'Недопустимый формат значения поля :attribute.',
    'numeric' => 'Значение поля :attribute должно быть числом.',
    'password' => [
        'letters' => 'Значение поля :attribute должно содержать минимум одну букву.',
        'mixed' => 'Значение поля :attribute должно содержать минимум одну заглавную и одну строчную букву.',
        'numbers' => 'Значение поля :attribute должно содержать минимум одну цифру.',
        'symbols' => 'Значение поля :attribute должно содержать минимум один символ.',
        'uncompromised' => 'Выбранное значение :attribute встречается в скомпрометированных данных. Выберите другое значение :attribute.',
    ],
    'present' => 'Поле :attribute должно быть заполнено.',
    'prohibited' => 'Поле :attribute должно быть пустым.',
    'prohibited_if' => 'Поле :attribute должно быть пустым, если поле :other имеет значение :value.',
    'prohibited_unless' => 'Поле :attribute должно быть пустым, если только поле :other не имеет значение :values.',
    'prohibits' => 'Поле :attribute не позволяет заполнить поле :other.',
    'regex' => 'Неверный формат значения поля :attribute.',
    'required' => 'Поле :attribute является обязательным.',
    'required_array_keys' => 'Поле :attribute должно содержать значения для: :values.',
    'required_if' => 'Поле :attribute должно быть заполнено, если поле :other имеет значение :value.',
    'required_if_accepted' => 'Поле :attribute должно быть заполнено, если принято поле :other.',
    'required_unless' => 'Поле :attribute должно быть заполнено, если только поле :other не имеет значение :values.',
    'required_with' => 'Поле :attribute должно быть заполнено, если заполнены какие-либо из полей :values.',
    'required_with_all' => 'Поле :attribute должно быть заполнено, если заполнены все поля :values.',
    'required_without' => 'Поле :attribute должно быть заполнено, если какие-либо из полей :values не заполнены.',
    'required_without_all' => 'Поле :attribute должно быть заполнено, если все поля :values не заполнены.',
    'same' => 'Значение поля :attribute должно совпадать с полем :other.',
    'size' => [
        'array' => 'Поле :attribute должно содержать минимум :size элемент.',
        'file' => 'Поле :attribute должно содержать файл размером :size КБ.',
        'numeric' => 'Значение поля :attribute должно равняться :size.',
        'string' => 'Значение поля :attribute должно содержать :size символ.',
    ],
    'starts_with' => 'Значение поля :attribute должно начинаться одним из следующих значений: :values.',
    'string' => 'Значение поля :attribute должно быть строкой.',
    'timezone' => 'Значение поля :attribute должно быть часовым поясом.',
    'unique' => ':attribute уже используется.',
    'uploaded' => 'Не удалось выгрузить :attribute.',
    'uppercase' => 'The :attribute field must be uppercase.',
    'url' => 'Поле :attribute должно содержать URL-адрес.',
    'ulid' => 'Поле :attribute должно содержать ULID.',
    'uuid' => 'Поле :attribute должно содержать UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'newImages.*' => [
            'image' => 'Загружаемые файлы могут быть только изображениями',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
