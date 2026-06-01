<?php

require_once __DIR__ . '/../Helpers.php';
require_once __DIR__ . '/../Services/AuthService.php';

class AuthController
{
    private AuthService $service;

    public function __construct()
    {
        $this->service = new AuthService();
    }

    public function login(): void
    {
        $data = getJsonInput();

        if (empty($data['username']) || empty($data['password'])) {
            Logger::error('Login validation failed', ['message' => 'Username and password required']);
            jsonResponse(['message' => 'Логин и пароль обязательны'], 422);
            return;
        }

        $session = $this->service->login($data['username'], $data['password']);

        if (!$session) {
            jsonResponse(['message' => 'Неверный логин или пароль'], 401);
            return;
        }

        jsonResponse($session, 200);
    }

    public function logout(): void
    {
        $token = getBearerToken();

        if (!$token) {
            jsonResponse(['message' => 'Token required'], 401);
            return;
        }

        if ($this->service->logout($token)) {
            jsonResponse(['message' => 'Вы успешно вышли'], 200);
        } else {
            jsonResponse(['message' => 'Invalid token'], 401);
        }
    }

    public function getCurrentUser(): void
    {
        $token = getBearerToken();

        if (!$token) {
            jsonResponse(['message' => 'Token required'], 401);
            return;
        }

        $user = $this->service->getCurrentUser($token);

        if (!$user) {
            jsonResponse(['message' => 'Invalid token'], 401);
            return;
        }

        jsonResponse($user, 200);
    }
}
