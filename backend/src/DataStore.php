<?php

class DataStore
{
    private static string $dataDir = __DIR__ . '/../data';
    private static string $usersFile = __DIR__ . '/../data/users.json';
    private static string $poemsFile = __DIR__ . '/../data/poems.json';

    public static array $users = [];
    public static array $poems = [];

    public static function init(): void
    {
        if (!is_dir(self::$dataDir)) {
            mkdir(self::$dataDir, 0755, true);
        }

        $logsDir = __DIR__ . '/../logs';
        if (!is_dir($logsDir)) {
            mkdir($logsDir, 0755, true);
        }

        $logFile = $logsDir . '/app.log';
        if (!file_exists($logFile)) {
            touch($logFile);
        }

        if (file_exists(self::$usersFile)) {
            self::$users = json_decode(file_get_contents(self::$usersFile), true) ?? [];
        } else {
            self::$users = [
                [
                    'id' => '1',
                    'name' => 'Анна',
                    'email' => 'anna@example.com',
                    'age' => 21,
                    'role' => 'admin'
                ],
                [
                    'id' => '2',
                    'name' => 'Мария',
                    'email' => 'maria@example.com',
                    'age' => 22,
                    'role' => 'editor'
                ]
            ];
            self::save();
        }

        if (file_exists(self::$poemsFile)) {
            self::$poems = json_decode(file_get_contents(self::$poemsFile), true) ?? [];
        } else {
            self::$poems = [
                [
                    'id' => '1',
                    'title' => 'Зимний вечер',
                    'author' => 'А. С. Пушкин',
                    'text' => "Буря мглою небо кроет,\nВихри снежные крутя...",
                    'genre' => 'Пейзажная лирика',
                    'createdBy' => 'Анна'
                ],
                [
                    'id' => '2',
                    'title' => 'Парус',
                    'author' => 'М. Ю. Лермонтов',
                    'text' => "Белеет парус одинокой\nВ тумане моря голубом!..",
                    'genre' => 'Лирика',
                    'createdBy' => 'Мария'
                ]
            ];
            self::save();
        }
    }

    public static function save(): void
    {
        file_put_contents(self::$usersFile, json_encode(self::$users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        file_put_contents(self::$poemsFile, json_encode(self::$poems, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}