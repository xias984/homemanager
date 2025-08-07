<?php
require_once("system/connection.php");

class PasswordService
{
    private $table = 'password_services';
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Inserisce un nuovo servizio
     */
    public function insertService($name, $description, $userid)
    {
        $query = "INSERT INTO $this->table (name, description, userid) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $name, $description, $userid);
        return $stmt->execute();
    }

    /**
     * Aggiorna un servizio esistente
     */
    public function updateService($id, $name, $description, $userid)
    {
        $query = "UPDATE $this->table SET name = ?, description = ? WHERE id = ? AND userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssii", $name, $description, $id, $userid);
        return $stmt->execute();
    }

    /**
     * Elimina un servizio
     */
    public function deleteService($id, $userid)
    {
        $query = "DELETE FROM $this->table WHERE id = ? AND userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $userid);
        return $stmt->execute();
    }

    /**
     * Seleziona tutti i servizi di un utente
     */
    public function selectServicesByUser($userid)
    {
        $query = "SELECT * FROM $this->table WHERE userid = ? ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Seleziona un servizio specifico
     */
    public function selectServiceById($id, $userid)
    {
        $query = "SELECT * FROM $this->table WHERE id = ? AND userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Conta il numero di password per servizio
     */
    public function countPasswordsByService($serviceid, $userid)
    {
        $query = "SELECT COUNT(*) as count FROM password_entries WHERE service_id = ? AND userid = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $serviceid, $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['count'];
    }
}
?> 