<?php
require_once("system/connection.php");

class PasswordEntry
{
    private $table = 'password_entries';
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Inserisce una nuova password
     */
    public function insertPassword($service_id, $username, $email, $password, $notes, $userid)
    {
        $query = "INSERT INTO $this->table (service_id, username, email, password, notes, userid) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssi", $service_id, $username, $email, $password, $notes, $userid);
        return $stmt->execute();
    }

    /**
     * Aggiorna una password esistente
     */
    public function updatePassword($id, $service_id, $username, $email, $password, $notes, $userid)
    {
        $query = "UPDATE $this->table SET service_id = ?, username = ?, email = ?, password = ?, notes = ? WHERE id = ? AND userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issssii", $service_id, $username, $email, $password, $notes, $id, $userid);
        return $stmt->execute();
    }

    /**
     * Elimina una password
     */
    public function deletePassword($id, $userid)
    {
        $query = "DELETE FROM $this->table WHERE id = ? AND userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $userid);
        return $stmt->execute();
    }

    /**
     * Seleziona tutte le password di un utente
     */
    public function selectPasswordsByUser($userid)
    {
        $query = "SELECT pe.*, ps.name as service_name 
                  FROM $this->table pe 
                  JOIN password_services ps ON pe.service_id = ps.id 
                  WHERE pe.userid = ? 
                  ORDER BY ps.name ASC, pe.username ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Seleziona le password per servizio
     */
    public function selectPasswordsByService($service_id, $userid)
    {
        $query = "SELECT * FROM $this->table WHERE service_id = ? AND userid = ? ORDER BY username ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $service_id, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Seleziona una password specifica
     */
    public function selectPasswordById($id, $userid)
    {
        $query = "SELECT pe.*, ps.name as service_name 
                  FROM $this->table pe 
                  JOIN password_services ps ON pe.service_id = ps.id 
                  WHERE pe.id = ? AND pe.userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Cerca password per termine
     */
    public function searchPasswords($search, $userid)
    {
        $search = "%$search%";
        $query = "SELECT pe.*, ps.name as service_name 
                  FROM $this->table pe 
                  JOIN password_services ps ON pe.service_id = ps.id 
                  WHERE pe.userid = ? AND (pe.username LIKE ? OR pe.email LIKE ? OR ps.name LIKE ? OR pe.notes LIKE ?)
                  ORDER BY ps.name ASC, pe.username ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("issss", $userid, $search, $search, $search, $search);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?> 