<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $conn;

    public function __construct() {
        try {
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_NAME');
            $username = getenv('DB_USER');
            $password = getenv('DB_PASS');

            // Enable SSL/TLS encryption if applicable
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            // Check if SSL/TLS encryption is supported by the database server
            if (getenv('DB_SSL_ENABLED')) {
                $options[PDO::MYSQL_ATTR_SSL_CA] = getenv('DB_SSL_CA');
                $options[PDO::MYSQL_ATTR_SSL_CERT] = getenv('DB_SSL_CERT');
                $options[PDO::MYSQL_ATTR_SSL_KEY] = getenv('DB_SSL_KEY');
            }

            $this->conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password, $options);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            // Log error to a secure location
            // Display a generic error message to the user
            die("Database connection failed");
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    // Log query execution time
    private function logQuery($query, $executionTime) {
        // You can implement your own logging mechanism here
        // For simplicity, we'll just echo the query and execution time
        echo "Executed query: $query (Execution Time: $executionTime seconds)<br>";
    }

    // Execute a SQL query and log its execution time
    private function executeQuery($query, $params = []) {
        $start = microtime(true);

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        $end = microtime(true);
        $executionTime = round($end - $start, 4);

        $this->logQuery($query, $executionTime);

        return $stmt;
    }

    // Begin a transaction
    public function beginTransaction() {
        $this->conn->beginTransaction();
    }

    // Commit a transaction
    public function commit() {
        $this->conn->commit();
    }

    // Rollback a transaction
    public function rollback() {
        $this->conn->rollback();
    }

    // Other methods...
}
