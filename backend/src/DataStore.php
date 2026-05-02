<?php

class DataStore
{
    public static array $users = [
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

    public static array $poems = [
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
}