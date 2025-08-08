<?php
require("./system/connection.php");

class PaymentType {
    public function __construct() {
        $this->table = 'finance_paymenttype';
    }

    public function createPaymentType($paymentTypeData) {
        global $conn;
        
        $query = "INSERT INTO $this->table (paymenttype, iduser, datainserimento) VALUES (
                '".$paymentTypeData['paymenttype']."', 
                ".$paymentTypeData['iduser'].", 
                '".$paymentTypeData['date']."'
            )";
        $result = mysqli_query($conn, $query);
        
        if ($result) {
            return true;
        }
    }

    public function getPaymentTypes() {
        global $conn;

        $query = "SELECT * FROM $this->table";
        $result = mysqli_query($conn, $query);

        $paymentTypes = array();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $paymentTypes[] = $row;
            }
        }

        return $paymentTypes;
    }

    /**
     * Ottiene i metodi di pagamento con paginazione e ordinamento lato database
     */
    public function getPaymentTypesPaginated($params = []) {
        require_once("./system/DatabasePagination.class.php");
        
        $pagination = new DatabasePagination($this->table, 'id', 'paymenttype', 'ASC');
        
        return $pagination->getPaginatedData([
            'page' => $params['page'] ?? 1,
            'itemsPerPage' => $params['itemsPerPage'] ?? 10,
            'sortColumn' => $params['sortColumn'] ?? 'paymenttype',
            'sortDirection' => $params['sortDirection'] ?? 'ASC'
        ]);
    }

    public function deletePaymentTypeById($id) {
        global $conn;

        $query = "DELETE FROM $this->table WHERE id = $id";

        return mysqli_query($conn, $query);
    }

    public function getPaymentTypeById($id) {
        global $conn;

        $query = "SELECT * FROM $this->table WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($result->num_rows === 1) {
            $paymentTypeInfo = $result->fetch_assoc();
            return $paymentTypeInfo;
        }
    }

    public function updatePaymentTypeById($paymentTypeData) {
        global $conn;

        $query = "UPDATE $this->table SET 
            paymenttype = '".$paymentTypeData['paymenttype']."', 
            iduser = ".$paymentTypeData['userid'].", 
            datainserimento = '".$paymentTypeData['datamodifica']."' 
            WHERE id = ". $paymentTypeData['id'];
    
        return mysqli_query($conn, $query);
    }
}