<?php

require_once __DIR__ . '/../Helpers.php';
require_once __DIR__ . '/../Services/PoemService.php';
require_once __DIR__ . '/../Validators/PoemValidator.php';

class PoemController
{
    private PoemService $service;

    public function __construct()
    {
        $this->service = new PoemService();
    }

    public function index(): void
    {
        Logger::info('Listed poems');
        jsonResponse($this->service->getAll());
    }

    public function show(string $id): void
    {
        $poem = $this->service->getById($id);

        if (!$poem) {
            Logger::error('Poem not found', ['id' => $id]);
            jsonResponse(['message' => 'Стихотворение не найдено'], 404);
        }

        jsonResponse($poem);
    }

    public function store(): void
    {
        $data = getJsonInput();
        $validation = PoemValidator::validate($data);

        if (!$validation['valid']) {
            Logger::error('Poem validation failed', ['message' => $validation['message']]);
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $poem = $this->service->create($data);
            Logger::info('Poem created', ['id' => $poem['id'], 'title' => $poem['title']]);
            jsonResponse($poem, 201);
        } catch (Exception $e) {
            Logger::error('Poem create failed', ['error' => $e->getMessage()]);
            jsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    public function update(string $id): void
    {
        $data = getJsonInput();
        $validation = PoemValidator::validate($data, true);

        if (!$validation['valid']) {
            Logger::error('Poem update validation failed', ['id' => $id, 'message' => $validation['message']]);
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $poem = $this->service->update($id, $data);
            Logger::info('Poem updated', ['id' => $id]);
            jsonResponse($poem);
        } catch (Exception $e) {
            Logger::error('Poem update failed', ['id' => $id, 'error' => $e->getMessage()]);
            jsonResponse(['message' => $e->getMessage()], 404);
        }
    }

    public function delete(string $id): void
    {
        try {
            $this->service->delete($id);
            Logger::info('Poem deleted', ['id' => $id]);
            jsonResponse(['message' => 'Стихотворение удалено']);
        } catch (Exception $e) {
            Logger::error('Poem delete failed', ['id' => $id, 'error' => $e->getMessage()]);
            jsonResponse(['message' => $e->getMessage()], 404);
        }
    }
}