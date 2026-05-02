<?php

require_once __DIR__ . '/../DataStore.php';

class UserService
{
    public function getAll(): array
    {
        return DataStore::$users;
    }

    public function getById(string $id): ?array
    {
        foreach (DataStore::$users as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public function create(array $data): array
    {
        foreach (DataStore::$users as $user) {
            if ($user['id'] === $data['id']) {
                throw new Exception('Пользователь с таким id уже существует');
            }
        }

        $newUser = [
            'id' => (string)$data['id'],
            'name' => $data['name'],
            'email' => $data['email'],
            'age' => $data['age'] ?? null
        ];

        DataStore::$users[] = $newUser;

        return $newUser;
    }
}