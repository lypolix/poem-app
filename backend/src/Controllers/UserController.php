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
        Logger::info('Listed users');
        jsonResponse($this->service->getAll());
    }

    public function show(string $id): void
    {
        $user = $this->service->getById($id);

        if (!$user) {
            Logger::error('User not found', ['id' => $id]);
            jsonResponse(['message' => 'Пользователь не найден'], 404);
        }

        jsonResponse($user);
    }

    public function store(): void
    {
        $data = getJsonInput();
        $validation = UserValidator::validate($data);

        if (!$validation['valid']) {
            Logger::error('User validation failed', ['message' => $validation['message']]);
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $user = $this->service->create($data);
            Logger::info('User created', ['id' => $user['id'], 'name' => $user['name']]);
            jsonResponse($user, 201);
        } catch (Exception $e) {
            Logger::error('User create failed', ['error' => $e->getMessage()]);
            jsonResponse(['message' => $e->getMessage()], 400);
        }
    }
}