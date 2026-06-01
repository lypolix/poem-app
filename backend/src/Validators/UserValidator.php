<?php

class UserValidator
{
    public static function validate(array $data): array
    {
        if (empty($data['username'])) {
            return ['valid' => false, 'message' => 'Поле username обязательно'];
        }

        if (strlen($data['username']) < 3) {
            return ['valid' => false, 'message' => 'Логин должен быть минимум 3 символа'];
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

        if (empty($data['password'])) {
            return ['valid' => false, 'message' => 'Поле password обязательно'];
        }

        if (strlen($data['password']) < 6) {
            return ['valid' => false, 'message' => 'Пароль должен быть минимум 6 символов'];
        }

        if (isset($data['age']) && $data['age'] !== null && !is_numeric($data['age'])) {
            return ['valid' => false, 'message' => 'Поле age должно быть числом'];
        }

        return ['valid' => true];
    }
}