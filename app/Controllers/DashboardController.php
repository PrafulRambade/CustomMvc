<?php
namespace App\Controllers;

class DashboardController{
	public function index(){
		view('dashboard');
	}
	public function test($data,$b){
		echo "<pre>";
		echo $data."--".$b;
	}

	public function logout(){
		// Unset specific session variables if needed
		unset($_SESSION['user_id']);
		unset($_SESSION['user_name']);
	
		// Regenerate session ID to prevent session fixation attacks
		session_regenerate_id(true);
	
		// Destroy the session
		session_destroy();
	
		// Redirect to the login page
		redirect('login');
	}
}