<?php

require_once __DIR__ . '/../Helpers.php';
require_once __DIR__ . '/../Services/UserService.php';
require_once __DIR__ . '/../Services/AuthService.php';
require_once __DIR__ . '/../Validators/UserValidator.php';

class UserController
{
    private UserService $service;
    private AuthService $authService;

    public function __construct()
    {
        $this->service = new UserService();
        $this->authService = new AuthService();
    }

    public function index(): void
    {
        Logger::info('Listed users');
        $users = $this->service->getAll();
        $usersWithoutPasswords = array_map(function($user) {
            unset($user['password']);
            return $user;
        }, $users);
        jsonResponse($usersWithoutPasswords);
    }

    public function show(string $id): void
    {
        $user = $this->service->getById($id);

        if (!$user) {
            Logger::error('User not found', ['id' => $id]);
            jsonResponse(['message' => 'Пользователь не найден'], 404);
        }

        unset($user['password']);
        jsonResponse($user);
    }

    public function store(): void
    {
        $token = getBearerToken();
        
        if (!$token || !$this->authService->validateToken($token)) {
            jsonResponse(['message' => 'Требуется аутентификация'], 401);
            return;
        }

        $currentUser = $this->authService->getCurrentUser($token);
        if ($currentUser['role'] !== 'admin') {
            Logger::error('Unauthorized user create', ['username' => $currentUser['username']]);
            jsonResponse(['message' => 'Только администратор может создавать пользователей'], 403);
            return;
        }

        $data = getJsonInput();
        $validation = UserValidator::validate($data);

        if (!$validation['valid']) {
            Logger::error('User validation failed', ['message' => $validation['message']]);
            jsonResponse(['message' => $validation['message']], 422);
        }

        try {
            $user = $this->service->create($data);
            unset($user['password']);
            Logger::info('User created', ['id' => $user['id'], 'username' => $user['username']]);
            jsonResponse($user, 201);
        } catch (Exception $e) {
            Logger::error('User create failed', ['error' => $e->getMessage()]);
            jsonResponse(['message' => $e->getMessage()], 400);
        }
    }
}