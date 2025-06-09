<?php
namespace App\Core;

abstract class Controller {
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = __DIR__ . "/../views/{$view}.php";
        
        if (!file_exists($viewFile)) {
            throw new \Exception("View {$view} not found");
        }
        
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        
        require __DIR__ . "/../views/layouts/main.php";
    }

    protected function redirect($url) {
        header("Location: {$url}");
        exit();
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getPost($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    protected function getQuery($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }
} 