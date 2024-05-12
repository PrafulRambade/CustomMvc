<?php
namespace App\Middleware;

class Guest {
    public function handle() {
        // If user is authenticated, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            redirect('dashboard');
            exit();
        }
        
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
    }
}
?>
