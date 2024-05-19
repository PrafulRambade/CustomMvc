<?php
namespace App\Controllers;
use App\Models\PropertyType;
use App\Config\Database;

class PropertyTypeController{
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    public function index(){
        view('admin.property_type.index');
    }
    public function getPropertyTypeDetails() {
        $request = $_POST;
        $draw = intval($request['draw']);
        $start = intval($request['start']);
        $length = intval($request['length']);
        $searchValue = $request['search']['value'];
        $orderColumnIndex = $request['order'][0]['column'];
        $orderDir = $request['order'][0]['dir'];
        $orderColumn = $request['columns'][$orderColumnIndex]['data'];

        $propertyType = new PropertyType($this->db->getConnection());
        $propertyTypeData = $propertyType->getFilteredPropertyTypes($start, $length, $searchValue, $orderColumn, $orderDir);
        $totalRecords = $propertyType->getTotalPropertyTypesCount();
        $filteredRecords = $propertyType->getFilteredPropertyTypesCount($searchValue);

        $response = [
            "draw" => $draw,
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $propertyTypeData
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
    
}