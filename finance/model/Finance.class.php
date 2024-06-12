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

    public function getTransactions($filters) {
        global $conn;
        $where = " WHERE 1 = 1";
    
        // Helper function to handle IN clauses
        $addInClause = function($field, $values) use (&$where) {
            if (!empty($values) && is_array($values)) {
                $sanitizedValues = array_map(function($value) {
                    return '"' . addslashes($value) . '"';
                }, $values);
                $where .= " AND $field IN (" . implode(',', $sanitizedValues) . ")";
            }
        };
    
        // Add conditions
        if (isset($filters['types'])) {
            $addInClause('type', $filters['types']);
        }
    
        if (isset($filters['categoryid'])) {
            $addInClause('categoryid', $filters['categoryid']);
        }
    
        if (isset($filters['paymenttypeid'])) {
            $addInClause('paymenttypeid', $filters['paymenttypeid']);
        }
    
        if (isset($filters['searchbox']) && !empty($filters['searchbox'])) {
            $searchbox = addslashes($filters['searchbox']);
            $where .= " AND description LIKE '%$searchbox%'";
        }

        if (isset($filters['payed']) && !empty($filters['payed'])) {
            $where .= " AND payed = 1";
        }

        if (isset($filters['notpayed']) && !empty($filters['notpayed'])) {
            $where .= " AND payed = 0";
        }

        if (isset($filters['periodo']) && !empty($filters['periodo']) && is_array($filters['periodo'])) {
            $sanitizedPeriods = array_map(function($period) {
                return (int) $period; // Ensure it's an integer
            }, $filters['periodo']);
            $where .= " AND MONTH(paymentdate) IN (" . implode(',', $sanitizedPeriods) . ")";
        }
    
        $query = "SELECT * FROM $this->table $where";
        $result = mysqli_query($conn, $query);
    
        $finances = array();
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $finances[] = $row;
            }
        }
    
        return $finances;
    }    
}