<?php

namespace App\Traits;

trait ResponseHandler
{

    public function responseFormatter($constant, $content = null, $errors = []): array
    {
        $constant = (array)$constant;
        $constant['content'] = $content;
        $constant['errors'] = $errors;
        return $constant;
    }

    public function errorProcessor($validator): array
    {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $errors[] = ['error_code' => $index, 'message' => translate($error[0])];
        }
        return $errors;
    }

}
