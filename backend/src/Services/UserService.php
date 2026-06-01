<?php

require_once __DIR__ . '/../DataStore.php';
require_once __DIR__ . '/../Logger.php';

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
        // Проверить на дублирование username
        foreach (DataStore::$users as $user) {
            if ($user['username'] === $data['username']) {
                Logger::error('User create duplicate username', ['username' => $data['username']]);
                throw new Exception('Пользователь с таким логином уже существует');
            }
        }

        $newId = (string)(max(array_map(fn($u) => (int)$u['id'], DataStore::$users)) + 1);

        $newUser = [
            'id' => $newId,
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'age' => $data['age'] ?? null,
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'editor'
        ];

        DataStore::$users[] = $newUser;
        DataStore::save();

        return $newUser;
    }
}