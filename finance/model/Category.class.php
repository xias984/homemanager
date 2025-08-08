<?php
require("./system/connection.php");

class Category {
    public function __construct() {
        $this->table = 'finance_category';
    }

    public function createCategory($categoryData) {
        global $conn;
        
        $query = "INSERT INTO $this->table (category, iduser, datainserimento) VALUES (
                '".$categoryData['category']."', 
                ".$categoryData['iduser'].", 
                '".$categoryData['date']."'
            )";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            return true;
        }
    }

    public function getCategory() {
        global $conn;

        $query = "SELECT * FROM $this->table";
        $result = mysqli_query($conn, $query);

        $categories = array();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $categories[] = $row;    
            }
            $row = mysqli_fetch_assoc($result);
        }

        return $categories;
    }

    /**
     * Ottiene le categorie con paginazione e ordinamento lato database
     */
    public function getCategoryPaginated($params = []) {
        require_once("./system/DatabasePagination.class.php");
        
        $pagination = new DatabasePagination($this->table, 'id', 'category', 'ASC');
        
        return $pagination->getPaginatedData([
            'page' => $params['page'] ?? 1,
            'itemsPerPage' => $params['itemsPerPage'] ?? 10,
            'sortColumn' => $params['sortColumn'] ?? 'category',
            'sortDirection' => $params['sortDirection'] ?? 'ASC'
        ]);
    }

    public function deleteCategoryById($id) {
        global $conn;

        $query = "DELETE FROM $this->table WHERE id = $id";

        return mysqli_query($conn, $query);
    }

    public function getCategoryById($id) {
        global $conn;

        $query = "SELECT * FROM $this->table WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result->num_rows === 1) {
            $categoryInfo = $result->fetch_assoc();
            return $categoryInfo;
        }
    }

    public function updateCategoryById($categoryData) {
        global $conn;

        $query = "UPDATE $this->table SET 
            category = '".$categoryData['category']."', 
            iduser = ".$categoryData['userid'].", 
            datainserimento = '".$categoryData['datamodifica']."' 
            WHERE id = ". $categoryData['id'];
    
        return mysqli_query($conn, $query);
    }
}
?>