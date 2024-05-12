<?php
function view($file_path, $data = []) {
    // Normalize the file path to prevent directory traversal attacks
    $file_path = realpath(APP_ROOT . '/pages/' . str_replace('.', DIRECTORY_SEPARATOR, $file_path) . '.php');

    if ($file_path && file_exists($file_path)) {
        // Extract the data array into variables
        extract($data);

        // Include the view file
        require $file_path;
    } else {
        // Throw an exception if the view file is not found
        throw new Exception('Page not found: ' . $file_path);
    }
}


function redirect($url) {
    // Check if the URL is a valid absolute or relative URL
    if (!filter_var($url, FILTER_VALIDATE_URL) && strpos($url, '/') !== 0) {
        // If it's not a valid URL, assume it's a relative URL and prepend with the base URL
        $url = getCurrentBaseUrl() . '/' . ltrim($url, '/');
    }

    // Set security headers to mitigate common web vulnerabilities
    header("Location: $url");
    header("X-Content-Type-Options: nosniff");
    header("X-Frame-Options: DENY");
    header("Referrer-Policy: strict-origin-when-cross-origin");

    // Ensure that the redirect is sent before exiting
    exit();
}

// Function to get the current base URL dynamically
function getCurrentBaseUrl() {
    $protocol = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $baseUrl = $protocol . '://' . $host;
    return $baseUrl;
}

function publicUrl($path = '') {
    // Determine the protocol (HTTP or HTTPS)
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    
    // Determine the base URL using the server's hostname and protocol
    $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'];

    // Construct the full public path URL by appending the path to the public directory
    $publicPath = $baseUrl . '/public';

    // Append the provided path (if any) to the public path URL
    if (!empty($path)) {
        $publicPath .= '/' . ltrim($path, '/');
    }

    // Return the generated public path URL
    return $publicPath;
}

function pageAdd($file_path) {
    // Validate the file path to prevent directory traversal attacks
    if (!preg_match('/^[a-zA-Z0-9\/\-_\.]+$/', $file_path)) {
        throw new Exception('Invalid file path: ' . $file_path);
    }

    // Construct the absolute path to the included file
    $absolute_path = APP_ROOT . '/pages/' . $file_path;

    // Check if the file exists and is allowed
    if (file_exists($absolute_path)) {
        // Include the file if it exists
        include $absolute_path;
    } else {
        // Throw an exception if the file is not found
        throw new Exception('Page not found: ' . $file_path);
	}	
}


function generateCsrfToken() {
	if (!isset($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}