<?php
namespace App\Controllers;

use App\Config\Database;
use App\Models\User;

class LoginController {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function index() {
        view('auth.login');
    }

    public function login() {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            // Invalid CSRF token, handle accordingly
            echo 'CSRF token validation failed';
            exit();
        }
    
        $user = new User($this->db->getConnection()); // Accessing connection using accessor method
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];

        if ($user->login()) {
            $_SESSION['user_id'] = $user->id;
            $_SESSION['user_name'] = $user->name;
            
            redirect('dashboard');
            exit();
        } else {
            echo 'Unable to login user';
        }
    }
}
