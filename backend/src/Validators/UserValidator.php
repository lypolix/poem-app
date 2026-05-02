<?php

class UserValidator
{
    public static function validate(array $data): array
    {
        if (empty($data['id'])) {
            return ['valid' => false, 'message' => 'Поле id обязательно'];
        }

        if (empty($data['name'])) {
            return ['valid' => false, 'message' => 'Поле name обязательно'];
        }

        if (empty($data['email'])) {
            return ['valid' => false, 'message' => 'Поле email обязательно'];
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['valid' => false, 'message' => 'Некорректный email'];
        }

        if (isset($data['age']) && $data['age'] !== null && !is_numeric($data['age'])) {
            return ['valid' => false, 'message' => 'Поле age должно быть числом'];
        }

        return ['valid' => true];
    }
}