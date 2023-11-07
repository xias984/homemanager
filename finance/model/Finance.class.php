<?php
require("./system/connection.php");

class Finance {
    public function __construct() {
        $this->table = 'finance';
    }

    public function createTransaction($transactionData) {
        global $conn;

        $query = "INSERT INTO $this->table (type, amount, userid, description, categoryid, paymentdate) VALUES (
                '".$transactionData['typeamount']."', 
                ".$transactionData['amount'].", 
                ".$transactionData['iduser'].",
                '".$transactionData['description']."',
                ".$transactionData['categoryid'].",
                '".$transactionData['paymentdate']."'
            )";
        $result = mysqli_query($conn, $query);

        if ($result) {
            return true;
        }
    }
}