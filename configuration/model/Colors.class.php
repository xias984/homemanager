<?php
require("./system/connection.php");

class Colors
{
    public function __construct() {
        $this->table = 'colors';
    }

    public function getColorsFromConfiguration($id_color) {
        global $conn;

        
        $query = "SELECT p.palette FROM $this->table as p 
                LEFT JOIN configuration as c on p.id = c.id_color 
                WHERE c.id_color = " . $id_color . ";";   
        $result = mysqli_query($conn, $query);
        
        
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
        }
        
        return $row;
    }

    public function getAllThemes() {
        global $conn;

        $query = "SELECT * FROM $this->table;";
        $result = mysqli_query($conn, $query);

        $themes = array();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $themes[] = $row;    
            }
            $row = mysqli_fetch_assoc($result);
        }

        return $themes;
    }

    public function getInfoColorById($id) {
        global $conn;

        $query = "SELECT description FROM $this->table WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result->num_rows > 0) {
            return mysqli_fetch_assoc($result);
        }
    }
}
?>