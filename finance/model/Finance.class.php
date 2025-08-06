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
            $periodConditions = [];
            
            foreach ($filters['periodo'] as $period) {
                if (strpos($period, '-') !== false) {
                    // Formato mese-anno (es: "06-2024")
                    list($month, $year) = explode('-', $period);
                    $month = (int) $month;
                    $year = (int) $year;
                    
                    if ($month >= 1 && $month <= 12 && $year > 0) {
                        $periodConditions[] = "(MONTH(paymentdate) = $month AND YEAR(paymentdate) = $year)";
                    }
                } else {
                    // Formato solo mese (backward compatibility)
                    $month = (int) $period;
                    if ($month >= 1 && $month <= 12) {
                        $periodConditions[] = "MONTH(paymentdate) = $month";
                    }
                }
            }
            
            // Verifica che ci siano condizioni valide
            if (!empty($periodConditions)) {
                $where .= " AND (" . implode(' OR ', $periodConditions) . ")";
            }
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
    
    public function getTransactionById($financeId) {
        global $conn;
        $query = "SELECT * FROM $this->table WHERE id = $financeId";
        $result = mysqli_query($conn, $query);
    
        if ($result && $result->num_rows > 0) {
            return mysqli_fetch_assoc($result);
        }
    }

    public function updatePayTransaction($financeId) {
        global $conn;

        $financeData = $this->getTransactionById($financeId);
        $payed = $financeData['payed'] == 1 ? 0 : 1;
        $query = "UPDATE $this->table SET payed = $payed WHERE id = $financeId";
        $result = mysqli_query($conn, $query);
    
        if ($result) {
            return true;
        }
    }

    public function updateTransactionById($transactionData) {
        global $conn;
        
        $query = "UPDATE $this->table SET 
                    type = '".addslashes($transactionData['type'])."',
                    amount = ".intval($transactionData['amount']).",
                    description = '".addslashes($transactionData['description'])."',
                    categoryid = ".intval($transactionData['categoryid']).",
                    paymenttypeid = ".intval($transactionData['paymenttypeid']).",
                    paymentdate = '".addslashes($transactionData['paymentdate'])."'
                  WHERE id = ".intval($transactionData['id']);
        
        $result = mysqli_query($conn, $query);

        if ($result) {
            return true;
        }
        return false;
    }

    public function deleteTransactionById($financeId) {
        global $conn;
        $query = "DELETE FROM $this->table WHERE id = $financeId";
        $result = mysqli_query($conn, $query);

        if ($result) {
            return true;
        }
    }
}