<?php
header("Content-Type: text/html; charset=utf-8");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Feature-Policy: accelerometer 'none'; camera 'none'; geolocation 'none'; microphone 'none'; payment 'none';");
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");
// header("Public-Key-Pins: pin-sha256=\"BASE64_HASH\"; pin-sha256=\"BACKUP_BASE64_HASH\"; max-age=5184000; includeSubDomains; report-uri=\"https://example.com/hpkp-report\"");


define('APP_ROOT', __dir__);
$envFilePath = __DIR__ . '/.env';

if (file_exists($envFilePath)) {
    $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Parse each line and set environment variables
    foreach ($lines as $line) {
        // Split the line into key and value
        list($key, $value) = explode('=', $line, 2);

        // Set the environment variable
        putenv("$key=$value");
        $_ENV[$key] = $value;
    }
}

require_once (APP_ROOT. '/vendor/autoload.php');


// autoloader for namespaced classes
spl_autoload_register(function($class){

	$classFile = str_replace("\\", DIRECTORY_SEPARATOR, $class.'.php');

	$classPath = APP_ROOT.'/app/'.$classFile;

	if(file_exists($classPath)){
		require_once($classPath);
	}
});

session_start();

use App\Services\Route;

// Security Headers
// header("Content-Security-Policy: default-src 'self'");
// header("X-Content-Type-Options: nosniff");
// header("X-XSS-Protection: 1; mode=block");
// header("Referrer-Policy: strict-origin-when-cross-origin");
// header("Content-Security-Policy: default-src 'self'; style-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net; style-src-elem 'self' https://cdn.jsdelivr.net; script-src-elem 'self' https://cdn.jsdelivr.net;");

$route = new Route();

require_once(APP_ROOT.'/routes/route.php');

$route->handle();
