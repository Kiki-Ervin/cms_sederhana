<?php
namespace App\Models;

use App\Core\Model;

class User extends Model {
    protected $table = 'users';

    public function findByUsername($username) {
        return $this->first('username = ?', [$username], 's');
    }

    public function findByEmail($email) {
        return $this->first('email = ?', [$email], 's');
    }

    public function updateLastLogin($id) {
        return $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function createUser($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->create([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function updatePassword($id, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hashedPassword]);
    }
} 