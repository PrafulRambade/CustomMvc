<?php

namespace App\Controllers;

use App\Config\Database;
use App\Models\User;

class RegisterController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        view('auth.register');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate inputs
            $name = htmlspecialchars($_POST['name']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['password']);

            // Hash password securely
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Check if user already exists
            $user = new User($this->db->getConnection()); // Assuming you have a User class
            $existingUser = $user->findByEmail($email);

            if ($existingUser) {
                echo 'User already exists';
                return;
            }

            // Create new user
            $user->name = $name;
            $user->email = $email;
            $user->password = $hashedPassword;

            if ($user->register()) {
                echo 'User registered successfully';
            } else {
                echo 'Unable to register user';
            }
        }
    }
}
