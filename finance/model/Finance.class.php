<?php
require("./system/connection.php");

class Finance {
    public function __construct() {
        $this->table = 'finance';
    }

    public function createTransaction($transactionData) {
        global $conn;

        $typeamount = mysqli_real_escape_string($conn, $transactionData['typeamount']);
        $amount = (float)$transactionData['amount'];
        $iduser = (int)$transactionData['iduser'];
        $description = mysqli_real_escape_string($conn, $transactionData['description']);
        $categoryid = (int)$transactionData['categoryid'];
        $paymenttypeid = (int)$transactionData['paymenttypeid'];
        $paymentdate = mysqli_real_escape_string($conn, $transactionData['paymentdate']);

        $installmentEndDate = isset($transactionData['installment_end_date']) && $transactionData['installment_end_date'] !== null
            ? "'" . mysqli_real_escape_string($conn, $transactionData['installment_end_date']) . "'"
            : "NULL";

        $query = "INSERT INTO $this->table (type, amount, userid, description, categoryid, paymenttypeid, paymentdate, installment_end_date) VALUES (
                '$typeamount',
                $amount,
                $iduser,
                '$description',
                $categoryid,
                $paymenttypeid,
                '$paymentdate',
                $installmentEndDate
            )";
        $result = mysqli_query($conn, $query);

        if ($result) {
            return true;
        }
    }

    public function registerInstallmentAmount($iduser, $typeamount, $totalAmount, $description, $categoryid, $paymenttypeid, $startDate, $endDate) {
        global $conn;

        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);

        $numMonths = 0;
        foreach ($period as $dt) {
            $numMonths++;
        }

        if ($numMonths === 0) {
            error_log("Errore: Numero di mesi per la rata è zero. Start: {$startDate}, End: {$endDate}");
            return false;
        }

        $success = true;
        foreach ($period as $dt) {
            $installmentDate = $dt->format('Y-m-d');
            
            $installmentDescription = $description . " (Rata " . $dt->format('m/Y') . ")";

            $transactionData = [
                'iduser' => $iduser,
                'typeamount' => $typeamount,
                'amount' => $totalAmount,
                'description' => $installmentDescription,
                'categoryid' => $categoryid,
                'paymenttypeid' => $paymenttypeid,
                'paymentdate' => $installmentDate,
                'installment_end_date' => $endDate
            ];

            if (!$this->createTransaction($transactionData)) {
                $success = false;
                error_log("Errore durante la registrazione della rata per il mese: " . $installmentDate);
                break;
            }
        }
        return $success;
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