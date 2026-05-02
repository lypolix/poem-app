<?php

require_once __DIR__ . '/../Helpers.php';
require_once __DIR__ . '/../Services/UserService.php';
require_once __DIR__ . '/../Validators/UserValidator.php';

class UserController
{
    private UserService $service;

    public function __construct()
    {
        $this->service = new UserService();
    }

    public function index(): void
    {
        jsonResponse($this->service->getAll());
    }

    public function show(string $id): void
    {
        $user = $this->service->getById($id);

        if (!$user) {
            jsonResponse(['message' => 'Пользователь не найден'], 404);
        }

        jsonResponse($user);
    }

    public function store(): void
    {
        $data = getJsonInput();
        $validation = UserValidator::validate($data);

        if (!$validation['valid']) {
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $user = $this->service->create($data);
            jsonResponse($user, 201);
        } catch (Exception $e) {
            jsonResponse(['message' => $e->getMessage()], 400);
        }
    }
}