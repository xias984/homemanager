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

    public function getInfoUserById($id) {
        global $conn;

        $query = "SELECT firstname, familyname, email, admin FROM $this->table WHERE id = ".$id;
        $result = mysqli_query($conn, $query);

        if ($result->num_rows === 1) {
            $userInfo = $result->fetch_assoc();
            return $userInfo;
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

    public function getUsers($wheAdmin = null) {
        global $conn;

        $where = !empty($wheAdmin) ? ' WHERE admin = 1' : '';

        $query = "SELECT id, firstname, familyname, email, admin FROM $this->table$where";
        $result = mysqli_query($conn, $query);

        $users = array();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;    
            }
            $row = mysqli_fetch_assoc($result);
        }

        return $users;
    }

    /**
     * Ottiene gli utenti con paginazione e ordinamento lato database
     */
    public function getUsersPaginated($params = []) {
        require_once("./system/DatabasePagination.class.php");
        
        $pagination = new DatabasePagination($this->table, 'id', 'firstname', 'ASC');
        
        $where = '';
        if (isset($params['admin']) && $params['admin'] !== null) {
            $where = "WHERE admin = " . (int)$params['admin'];
        }
        
        $result = $pagination->getPaginatedData([
            'page' => $params['page'] ?? 1,
            'itemsPerPage' => $params['itemsPerPage'] ?? 10,
            'sortColumn' => $params['sortColumn'] ?? 'firstname',
            'sortDirection' => $params['sortDirection'] ?? 'ASC',
            'where' => $where
        ]);
        
        return $result;
    }

    public function deleteUserById($id) {
        global $conn;

        $query = "DELETE FROM $this->table WHERE id = $id";

        return mysqli_query($conn, $query);
    }

    public function updateUserById($id, $userData) {
        global $conn;

        $query = "UPDATE $this->table SET firstname = '$userData[0]', familyname = '$userData[1]', email = '$userData[2]', admin = $userData[3] WHERE id = $id";
    
        return mysqli_query($conn, $query);
    }
    
}