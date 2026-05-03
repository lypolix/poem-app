<?php
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowedOrigins = ['http://localhost:5173', 'http://localhost:3000'];

if (in_array($origin, $allowedOrigins)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
}

header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept');
header('Access-Control-Expose-Headers: Content-Type');
header('Vary: Origin');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../src/Helpers.php';
require_once __DIR__ . '/../src/Logger.php';
require_once __DIR__ . '/../src/DataStore.php';
require_once __DIR__ . '/../src/Router.php';
require_once __DIR__ . '/../src/Controllers/UserController.php';
require_once __DIR__ . '/../src/Controllers/PoemController.php';

DataStore::init();
Logger::info('Backend started', ['uri' => $_SERVER['REQUEST_URI'] ?? '']);

$router = new Router();

$userController = new UserController();
$poemController = new PoemController();

$router->add('GET', '/users', [$userController, 'index']);
$router->add('GET', '/users/:id', [$userController, 'show']);
$router->add('POST', '/users', [$userController, 'store']);

$router->add('GET', '/poems', [$poemController, 'index']);
$router->add('GET', '/poems/:id', [$poemController, 'show']);
$router->add('POST', '/poems', [$poemController, 'store']);
$router->add('PUT', '/poems/:id', [$poemController, 'update']);
$router->add('DELETE', '/poems/:id', [$poemController, 'delete']);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);