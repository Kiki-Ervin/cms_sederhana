<?php
session_start();

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Autoload classes
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Create router instance
$router = new App\Core\Router();

// Define routes
$router->get('/', ['HomeController', 'index']);
$router->get('/login', ['AuthController', 'loginForm']);
$router->post('/login', ['AuthController', 'login']);
$router->get('/register', ['AuthController', 'registerForm']);
$router->post('/register', ['AuthController', 'register']);
$router->get('/logout', ['AuthController', 'logout']);

$router->get('/posts', ['PostController', 'index']);
$router->get('/posts/create', ['PostController', 'create']);
$router->post('/posts/store', ['PostController', 'store']);
$router->get('/posts/edit/{id}', ['PostController', 'edit']);
$router->post('/posts/update/{id}', ['PostController', 'update']);
$router->post('/posts/delete/{id}', ['PostController', 'delete']);

$router->get('/account', ['AccountController', 'index']);
$router->post('/account/update', ['AccountController', 'update']);

// Handle 404
$router->notFound(function() {
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
});

// Dispatch the request
$router->dispatch(); 