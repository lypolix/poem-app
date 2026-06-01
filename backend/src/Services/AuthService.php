<?php

require_once __DIR__ . '/../DataStore.php';
require_once __DIR__ . '/../Logger.php';

class AuthService
{
    public function login(string $username, string $password): ?array
    {
        foreach (DataStore::$users as $user) {
            if ($user['username'] === $username) {
                if (password_verify($password, $user['password'])) {
                    $token = bin2hex(random_bytes(32));
                    $session = [
                        'token' => $token,
                        'userId' => $user['id'],
                        'username' => $user['username'],
                        'name' => $user['name'],
                        'role' => $user['role'],
                        'createdAt' => time()
                    ];
                    
                    DataStore::$sessions[$token] = $session;
                    DataStore::save();
                    Logger::info('User logged in', ['username' => $username]);
                    
                    return $session;
                }
                Logger::error('Wrong password', ['username' => $username]);
                return null;
            }
        }
        Logger::error('User not found', ['username' => $username]);
        return null;
    }

    public function logout(string $token): bool
    {
        if (isset(DataStore::$sessions[$token])) {
            $username = DataStore::$sessions[$token]['username'];
            unset(DataStore::$sessions[$token]);
            DataStore::save();
            Logger::info('User logged out', ['username' => $username]);
            return true;
        }
        return false;
    }

    public function getCurrentUser(string $token): ?array
    {
        return DataStore::$sessions[$token] ?? null;
    }

    public function validateToken(string $token): bool
    {
        return isset(DataStore::$sessions[$token]);
    }

    public function requireRole(string $token, string $requiredRole): bool
    {
        $session = $this->getCurrentUser($token);
        if (!$session) {
            return false;
        }
        return $session['role'] === $requiredRole || $session['role'] === 'admin';
    }
}
