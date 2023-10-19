<?php
require("./system/connection.php");

class Auth {
    public function __construct() {
        $this->table = 'users';
    }

    public function createUser($firstname, $familyname, $email, $password, $admin) {
        global $conn;

        $query = "INSERT INTO $this->table (firstname, familyname, email, password, admin) VALUES (
            '$firstname', '$familyname', '$email', '$password', $admin
        );";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            return true;
        }
    }   

    public function checkEmailIfExist($email) {
        global $conn;
    
        $query = "SELECT email FROM $this->table WHERE email = '" . $email . "';";
        $result = mysqli_query($conn, $query);
    
        if ($result->num_rows > 0) {
            return true; // L'email esiste nel database
        } else {
            return false; // L'email non esiste nel database
        }
    }

    public function checkPassword($email, $password) {
        global $conn;

        $query = "SELECT password FROM $this->table WHERE email = '" . $email . "';";
        $result = mysqli_query($conn, $query);

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();
            $storedPassword = $row['password'];

            // Verifica se la password corrisponde a quella criptata
            if (password_verify($password, $storedPassword)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getInfoUserByEmail($email) {
        global $conn;

        $query = "SELECT id, firstname, familyname, email, admin FROM $this->table WHERE email = '" . $email . "';";
        $result = mysqli_query($conn, $query);

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
    }

    public function updatePasswordById($id, $password) {
        global $conn;

        $query = "UPDATE $this->table SET password = '$password' WHERE id = $id;";
        $result = mysqli_query($conn, $query);

        return $result;
    }

    public function getPasswordById() {
        global $conn;

        $query = "SELECT password FROM $this->table WHERE id = " . $_SESSION['iduser'];
        $result = mysqli_query($conn, $query);

        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
    }
}