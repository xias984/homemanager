<?php
require("./system/connection.php");

class Configuration
{
    public function __construct() {
        $this->table = 'configuration';
    }

    public function getConfiguration() {
        global $conn;
        
        $query = "SELECT name, description, maintenance, logo, id_color FROM configuration";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        
        return $row;
    }

    public function saveTheme($id) {
        global $conn;

        $query = "UPDATE $this->table SET id_color = " . $id;
        $result = mysqli_query($conn, $query);
        
        return $result;
    }

    public function storeLogo($image) {
        global $conn;


        $query = "UPDATE $this->table SET logo = '" . $image . "';";
        $result = mysqli_query($conn, $query);

        return $result;
    }

    public function saveInfoSite($name, $description) {
        global $conn;
    
        $updates = array();
    
        if ($name) {
            $updates[] = "name = '" . mysqli_real_escape_string($conn, $name) . "'";
        }
    
        if ($description) {
            $updates[] = "description = '" . mysqli_real_escape_string($conn, $description) . "'";
        }
    
        if (!empty($updates)) {
            $updateString = implode(", ", $updates);
    
            $query = "UPDATE $this->table SET $updateString;";
            $result = mysqli_query($conn, $query);
    
            return $result;
        }
    
        return false;
    }
    
}
?>