<?php

require_once __DIR__ . '/../DataStore.php';

class PoemService
{
    public function getAll(): array
    {
        return DataStore::$poems;
    }

    public function getById(string $id): ?array
    {
        foreach (DataStore::$poems as $poem) {
            if ($poem['id'] === $id) {
                return $poem;
            }
        }
        return null;
    }

    public function create(array $data): array
    {
        foreach (DataStore::$poems as $poem) {
            if ($poem['id'] === $data['id']) {
                throw new Exception('Стихотворение с таким id уже существует');
            }
        }

        $newPoem = [
            'id' => (string)$data['id'],
            'title' => $data['title'],
            'author' => $data['author'],
            'text' => $data['text'],
            'genre' => $data['genre'] ?? '',
            'createdBy' => $data['createdBy'] ?? ''
        ];

        DataStore::$poems[] = $newPoem;

        return $newPoem;
    }

    public function update(string $id, array $data): array
    {
        foreach (DataStore::$poems as $index => $poem) {
            if ($poem['id'] === $id) {
                DataStore::$poems[$index] = [
                    ...$poem,
                    'title' => $data['title'],
                    'author' => $data['author'],
                    'text' => $data['text'],
                    'genre' => $data['genre'] ?? '',
                    'createdBy' => $data['createdBy'] ?? ''
                ];

                return DataStore::$poems[$index];
            }
        }

        throw new Exception('Стихотворение не найдено');
    }

    public function delete(string $id): void
    {
        foreach (DataStore::$poems as $index => $poem) {
            if ($poem['id'] === $id) {
                array_splice(DataStore::$poems, $index, 1);
                return;
            }
        }

        throw new Exception('Стихотворение не найдено');
    }
}