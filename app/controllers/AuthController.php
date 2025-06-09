<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function loginForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->view('auth/login', ['title' => 'Login']);
    }

    public function login() {
        if (!$this->isPost()) {
            $this->redirect('/login');
        }

        $username = $this->getPost('username');
        $password = $this->getPost('password');

        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $this->userModel->updateLastLogin($user['id']);
            $this->redirect('/');
        }

        $this->view('auth/login', [
            'title' => 'Login',
            'error' => 'Invalid username or password'
        ]);
    }

    public function registerForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        $this->view('auth/register', ['title' => 'Register']);
    }

    public function register() {
        if (!$this->isPost()) {
            $this->redirect('/register');
        }

        $username = trim($this->getPost('username'));
        $email = trim($this->getPost('email'));
        $password = $this->getPost('password');
        $confirmPassword = $this->getPost('confirm_password');

        $errors = [];

        if (empty($username)) {
            $errors[] = 'Username is required';
        }
        if (empty($email)) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        if (empty($password)) {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }

        if (empty($errors)) {
            if ($this->userModel->findByUsername($username)) {
                $errors[] = 'Username already exists';
            }
            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Email already exists';
            }
        }

        if (empty($errors)) {
            $this->userModel->createUser($username, $email, $password);
            $this->redirect('/login');
        }

        $this->view('auth/register', [
            'title' => 'Register',
            'errors' => $errors,
            'username' => $username,
            'email' => $email
        ]);
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
} 