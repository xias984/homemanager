<?php
require("./system/connection.php");

class Finance {
    public function __construct() {
        $this->table = 'finance';
    }

    public function createTransaction($transactionData) {
        global $conn;

        $query = "INSERT INTO $this->table (type, amount, userid, description, categoryid, paymenttypeid, paymentdate) VALUES (
                '".$transactionData['typeamount']."', 
                ".$transactionData['amount'].", 
                ".$transactionData['iduser'].",
                '".$transactionData['description']."',
                ".$transactionData['categoryid'].",
                ".$transactionData['paymenttypeid'].",
                '".$transactionData['paymentdate']."'
            )";
        $result = mysqli_query($conn, $query);

        if ($result) {
            return true;
        }
    }

    public function getTransactions() {
        global $conn;

        $query = "SELECT * FROM $this->table";
        $result = mysqli_query($conn, $query);

        $finances = array();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $finances[] = $row;    
            }
            $row = mysqli_fetch_assoc($result);
        }

        return $finances;
    }
}