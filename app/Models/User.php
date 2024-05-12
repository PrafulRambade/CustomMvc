<?php 
namespace App\Models;

use PDO;

class User extends BaseModel {
    private $table_name = "users";
    public $id;
    public $name;
    public $email;
    public $password;
    public $conn;

    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function register() {
        // Check if the email already exists
        $query = "SELECT * FROM ".$this->table_name. " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return false; // User already exists
        }

        // Insert new user
        $query = "INSERT INTO ".$this->table_name. " (name,email,password) VALUES (:name,:email,:password)";
        $stmt = $this->conn->prepare($query);

        // Hash the password securely
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $hashed_password);

        return $stmt->execute();
    }

    public function login() {
        // Retrieve user by email
        $query = "SELECT * FROM ".$this->table_name. " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password
        if ($row && password_verify($this->password, $row['password'])) {
            $this->id = $row['id'];
            $this->name = $row['name'];
            return true;
        } else {
            return false;
        }
    }
	public function findByEmail($email) {
        $query = "SELECT * FROM $this->table_name WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // Add more methods as needed...
}
?>
