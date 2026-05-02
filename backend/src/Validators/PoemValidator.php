<?php

class PoemValidator
{
    public static function validate(array $data, bool $isUpdate = false): array
    {
        if (!$isUpdate && empty($data['id'])) {
            return ['valid' => false, 'message' => 'Поле id обязательно'];
        }

        if (empty($data['title'])) {
            return ['valid' => false, 'message' => 'Поле title обязательно'];
        }

        if (empty($data['author'])) {
            return ['valid' => false, 'message' => 'Поле author обязательно'];
        }

        if (empty($data['text'])) {
            return ['valid' => false, 'message' => 'Поле text обязательно'];
        }

        return ['valid' => true];
    }
}