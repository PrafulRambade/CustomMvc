<?php 
namespace App\Models;
use PDO;

class PropertyType extends BaseModel{
	private $table_name = "property_types";
	public $property_type_name;
    public $id;
	public $conn;

	public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    public function getFilteredPropertyTypes($start, $length, $searchValue, $orderColumn, $orderDir) {
        $query = "SELECT * FROM {$this->table_name} 
                  WHERE property_type_name LIKE :search 
                  ORDER BY {$orderColumn} {$orderDir} 
                  LIMIT :start, :length";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$searchValue}%";
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':start', $start, PDO::PARAM_INT);
        $stmt->bindParam(':length', $length, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalPropertyTypesCount() {
        $query = "SELECT COUNT(*) as count FROM {$this->table_name}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }

    public function getFilteredPropertyTypesCount($searchValue) {
        $query = "SELECT COUNT(*) as count FROM {$this->table_name} WHERE property_type_name LIKE :search";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$searchValue}%";
        $stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
}
