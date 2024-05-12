<?php
namespace App\Middleware;

class Auth {
    public function handle() {
        // If user is not authenticated, redirect to login
        if (!isset($_SESSION['user_id'])) {
            redirect('login');
            exit();
        }
        
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
    }
}
?>
