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
        jsonResponse($this->service->getAll());
    }

    public function show(string $id): void
    {
        $poem = $this->service->getById($id);

        if (!$poem) {
            jsonResponse(['message' => 'Стихотворение не найдено'], 404);
        }

        jsonResponse($poem);
    }

    public function store(): void
    {
        $data = getJsonInput();
        $validation = PoemValidator::validate($data);

        if (!$validation['valid']) {
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $poem = $this->service->create($data);
            jsonResponse($poem, 201);
        } catch (Exception $e) {
            jsonResponse(['message' => $e->getMessage()], 400);
        }
    }

    public function update(string $id): void
    {
        $data = getJsonInput();
        $validation = PoemValidator::validate($data, true);

        if (!$validation['valid']) {
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $poem = $this->service->update($id, $data);
            jsonResponse($poem);
        } catch (Exception $e) {
            jsonResponse(['message' => $e->getMessage()], 404);
        }
    }

    public function delete(string $id): void
    {
        try {
            $this->service->delete($id);
            jsonResponse(['message' => 'Стихотворение удалено']);
        } catch (Exception $e) {
            jsonResponse(['message' => $e->getMessage()], 404);
        }
    }
}