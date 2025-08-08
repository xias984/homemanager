<?php

class DatabasePagination {
    private $conn;
    private $table;
    private $primaryKey;
    private $defaultOrderBy;
    private $defaultOrderDirection;

    public function __construct($table, $primaryKey = 'id', $defaultOrderBy = 'id', $defaultOrderDirection = 'DESC') {
        global $conn;
        $this->conn = $conn;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->defaultOrderBy = $defaultOrderBy;
        $this->defaultOrderDirection = $defaultOrderDirection;
    }

    /**
     * Esegue una query con paginazione e ordinamento
     */
    public function getPaginatedData($params = []) {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int)$params['itemsPerPage'] : 10;
        $sortColumn = isset($params['sortColumn']) ? $params['sortColumn'] : $this->defaultOrderBy;
        $sortDirection = isset($params['sortDirection']) ? strtoupper($params['sortDirection']) : $this->defaultOrderDirection;
        $where = isset($params['where']) ? $params['where'] : '';
        $joins = isset($params['joins']) ? $params['joins'] : '';

        // Validazione parametri
        $page = max(1, $page);
        $itemsPerPage = max(1, min(100, $itemsPerPage)); // Limita a 100 elementi max
        $sortDirection = in_array($sortDirection, ['ASC', 'DESC']) ? $sortDirection : $this->defaultOrderDirection;

        // Validazione colonna di ordinamento
        $validColumns = $this->getValidColumns();
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = $this->defaultOrderBy;
        }

        // Calcola offset
        $offset = ($page - 1) * $itemsPerPage;

        // Query per il conteggio totale
        $countQuery = "SELECT COUNT(*) as total FROM $this->table $joins $where";
        $countResult = mysqli_query($this->conn, $countQuery);
        if (!$countResult) {
            error_log("Database error in count query: " . mysqli_error($this->conn));
            return ['data' => [], 'pagination' => ['currentPage' => 1, 'itemsPerPage' => $itemsPerPage, 'totalItems' => 0, 'totalPages' => 0, 'hasNextPage' => false, 'hasPrevPage' => false]];
        }
        $totalItems = mysqli_fetch_assoc($countResult)['total'];

        // Query principale con paginazione
        $query = "SELECT * FROM $this->table $joins $where ORDER BY $sortColumn $sortDirection LIMIT $itemsPerPage OFFSET $offset";
        $result = mysqli_query($this->conn, $query);

        if (!$result) {
            error_log("Database error in main query: " . mysqli_error($this->conn));
            return ['data' => [], 'pagination' => ['currentPage' => $page, 'itemsPerPage' => $itemsPerPage, 'totalItems' => $totalItems, 'totalPages' => ceil($totalItems / $itemsPerPage), 'hasNextPage' => false, 'hasPrevPage' => false]];
        }

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return [
            'data' => $data,
            'pagination' => [
                'currentPage' => $page,
                'itemsPerPage' => $itemsPerPage,
                'totalItems' => $totalItems,
                'totalPages' => ceil($totalItems / $itemsPerPage),
                'hasNextPage' => $page < ceil($totalItems / $itemsPerPage),
                'hasPrevPage' => $page > 1
            ]
        ];
    }

    /**
     * Ottiene le colonne valide per l'ordinamento
     */
    private function getValidColumns() {
        $query = "DESCRIBE $this->table";
        $result = mysqli_query($this->conn, $query);
        $columns = [];
        
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $columns[] = $row['Field'];
            }
        }
        
        return $columns;
    }

    /**
     * Esegue una query con JOIN e paginazione
     */
    public function getPaginatedDataWithJoins($select, $joins, $where = '', $params = []) {
        $page = isset($params['page']) ? (int)$params['page'] : 1;
        $itemsPerPage = isset($params['itemsPerPage']) ? (int)$params['itemsPerPage'] : 10;
        $sortColumn = isset($params['sortColumn']) ? $params['sortColumn'] : $this->defaultOrderBy;
        $sortDirection = isset($params['sortDirection']) ? strtoupper($params['sortDirection']) : $this->defaultOrderDirection;

        // Validazione parametri
        $page = max(1, $page);
        $itemsPerPage = max(1, min(100, $itemsPerPage));
        $sortDirection = in_array($sortDirection, ['ASC', 'DESC']) ? $sortDirection : $this->defaultOrderDirection;

        // Calcola offset
        $offset = ($page - 1) * $itemsPerPage;

        // Query per il conteggio totale
        $countQuery = "SELECT COUNT(*) as total FROM $this->table $joins $where";
        $countResult = mysqli_query($this->conn, $countQuery);
        $totalItems = mysqli_fetch_assoc($countResult)['total'];

        // Query principale con paginazione
        $query = "SELECT $select FROM $this->table $joins $where ORDER BY $sortColumn $sortDirection LIMIT $itemsPerPage OFFSET $offset";
        $result = mysqli_query($this->conn, $query);

        $data = [];
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }

        return [
            'data' => $data,
            'pagination' => [
                'currentPage' => $page,
                'itemsPerPage' => $itemsPerPage,
                'totalItems' => $totalItems,
                'totalPages' => ceil($totalItems / $itemsPerPage),
                'hasNextPage' => $page < ceil($totalItems / $itemsPerPage),
                'hasPrevPage' => $page > 1
            ]
        ];
    }

    /**
     * Genera la clausola WHERE per i filtri
     */
    public function buildWhereClause($filters = []) {
        $conditions = [];
        
        foreach ($filters as $field => $value) {
            if ($value !== null && $value !== '') {
                if (is_array($value)) {
                    // Gestione array (IN clause)
                    $sanitizedValues = array_map(function($v) {
                        return "'" . mysqli_real_escape_string($this->conn, $v) . "'";
                    }, $value);
                    $conditions[] = "$field IN (" . implode(',', $sanitizedValues) . ")";
                } else {
                    // Gestione singolo valore
                    $escapedValue = mysqli_real_escape_string($this->conn, $value);
                    $conditions[] = "$field = '$escapedValue'";
                }
            }
        }
        
        return !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
    }

    /**
     * Genera la clausola WHERE per ricerche LIKE
     */
    public function buildSearchClause($searchFields = [], $searchTerm = '') {
        if (empty($searchTerm) || empty($searchFields)) {
            return '';
        }

        $conditions = [];
        $escapedTerm = mysqli_real_escape_string($this->conn, $searchTerm);
        
        foreach ($searchFields as $field) {
            $conditions[] = "$field LIKE '%$escapedTerm%'";
        }
        
        return 'WHERE ' . implode(' OR ', $conditions);
    }

    /**
     * Combina più clausole WHERE
     */
    public function combineWhereClauses($clauses = []) {
        $validClauses = array_filter($clauses, function($clause) {
            return !empty(trim($clause));
        });
        
        if (empty($validClauses)) {
            return '';
        }
        
        return 'WHERE ' . implode(' AND ', array_map(function($clause) {
            return '(' . trim($clause, 'WHERE ') . ')';
        }, $validClauses));
    }
} 