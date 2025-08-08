<?php

class TableHelper {
    private $itemsPerPage = 10;
    private $currentPage = 1;
    private $sortColumn = '';
    private $sortDirection = 'ASC';
    private $totalItems = 0;
    private $data = [];
    private $paginationInfo = null;
    private $useDatabasePagination = false;
    private $columnMapping = [];

    public function __construct($data = [], $itemsPerPage = 10) {
        $this->data = $data;
        $this->itemsPerPage = $itemsPerPage;
        $this->initializeFromRequest();
    }

    /**
     * Costruttore per paginazione lato database
     */
    public static function createWithDatabasePagination($paginationInfo, $itemsPerPage = 10) {
        $instance = new self([], $itemsPerPage);
        $instance->paginationInfo = $paginationInfo;
        $instance->useDatabasePagination = true;
        $instance->initializeFromRequest();
        return $instance;
    }

    /**
     * Imposta i dati per la TableHelper
     */
    public function setData($data) {
        $this->data = $data;
    }

    private function initializeFromRequest() {
        // Gestione elementi per pagina
        if (isset($_GET['itemsPerPage']) && is_numeric($_GET['itemsPerPage']) && $_GET['itemsPerPage'] > 0) {
            $this->itemsPerPage = (int)$_GET['itemsPerPage'];
        }

        // Gestione paginazione
        if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) {
            $this->currentPage = (int)$_GET['page'];
        }

        // Gestione ordinamento
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            $this->sortColumn = $_GET['sort'];
        }

        if (isset($_GET['direction']) && in_array(strtoupper($_GET['direction']), ['ASC', 'DESC'])) {
            $this->sortDirection = strtoupper($_GET['direction']);
        }


    }

    public function getPaginatedData() {
        if ($this->useDatabasePagination && $this->paginationInfo) {
            // Usa i dati già paginati dal database
            $headers = [];
            $data = $this->data;
            
            if (isset($this->data[0]) && is_array($this->data[0])) {
                $headers = $this->data[0];
                $data = array_slice($this->data, 1);
            }

            // Ricostruisci l'array con intestazione
            if (!empty($headers)) {
                array_unshift($data, $headers);
            }

            return $data;
        }

        if (empty($this->data)) {
            return [];
        }

        // Rimuovi l'intestazione se presente
        $headers = [];
        $data = $this->data;
        
        if (isset($this->data[0]) && is_array($this->data[0])) {
            $headers = $this->data[0];
            $data = array_slice($this->data, 1);
        }

        $this->totalItems = count($data);

        // Ordinamento (escludi l'ultima colonna che contiene le azioni)
        if (!empty($this->sortColumn) && is_numeric($this->sortColumn)) {
            $data = $this->sortData($data, (int)$this->sortColumn);
        }

        // Paginazione
        $offset = ($this->currentPage - 1) * $this->itemsPerPage;
        $paginatedData = array_slice($data, $offset, $this->itemsPerPage);

        // Ricostruisci l'array con intestazione
        if (!empty($headers)) {
            array_unshift($paginatedData, $headers);
        }

        return $paginatedData;
    }

    private function sortData($data, $columnIndex) {
        usort($data, function($a, $b) use ($columnIndex) {
            $aValue = isset($a[$columnIndex]) ? $a[$columnIndex] : '';
            $bValue = isset($b[$columnIndex]) ? $b[$columnIndex] : '';

            // Rimuovi tag HTML per l'ordinamento
            $aValue = strip_tags($aValue);
            $bValue = strip_tags($bValue);

            // Gestione numeri (inclusi quelli con simboli di valuta)
            $aValueClean = preg_replace('/[^0-9.-]/', '', $aValue);
            $bValueClean = preg_replace('/[^0-9.-]/', '', $bValue);
            
            if (is_numeric($aValueClean) && is_numeric($bValueClean)) {
                $aValue = (float)$aValueClean;
                $bValue = (float)$bValueClean;
            } else {
                $aValue = strtolower($aValue);
                $bValue = strtolower($bValue);
            }

            if ($this->sortDirection === 'ASC') {
                return $aValue <=> $bValue;
            } else {
                return $bValue <=> $aValue;
            }
        });

        return $data;
    }

    public function getPaginationInfo() {
        if ($this->useDatabasePagination && $this->paginationInfo) {
            // Usa le informazioni di paginazione dal database
            return [
                'currentPage' => $this->paginationInfo['currentPage'],
                'totalPages' => $this->paginationInfo['totalPages'],
                'totalItems' => $this->paginationInfo['totalItems'],
                'itemsPerPage' => $this->paginationInfo['itemsPerPage'],
                'startItem' => ($this->paginationInfo['currentPage'] - 1) * $this->paginationInfo['itemsPerPage'] + 1,
                'endItem' => min($this->paginationInfo['currentPage'] * $this->paginationInfo['itemsPerPage'], $this->paginationInfo['totalItems'])
            ];
        }

        $totalPages = ceil($this->totalItems / $this->itemsPerPage);
        
        return [
            'currentPage' => $this->currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $this->totalItems,
            'itemsPerPage' => $this->itemsPerPage,
            'startItem' => ($this->currentPage - 1) * $this->itemsPerPage + 1,
            'endItem' => min($this->currentPage * $this->itemsPerPage, $this->totalItems)
        ];
    }

    public function createSortableHeader($columnIndex, $columnName, $currentPage = null) {
        // Non rendere cliccabile l'ultima colonna (Actions)
        if (strpos(strtolower($columnName), 'action') !== false || strpos(strtolower($columnName), 'azioni') !== false) {
            return $columnName;
        }

        // Mappa gli indici delle colonne ai nomi delle colonne del database
        $columnMapping = $this->getColumnMapping();
        $dbColumn = isset($columnMapping[$columnIndex]) ? $columnMapping[$columnIndex] : $columnIndex;

        $currentSort = $this->sortColumn == $dbColumn ? $this->sortDirection : '';
        $nextDirection = ($currentSort === 'ASC') ? 'DESC' : 'ASC';
        
        $url = $this->buildUrl([
            'sort' => $dbColumn,
            'direction' => $nextDirection,
            'page' => $currentPage ?: $this->currentPage
        ]);

        $sortIcon = '';
        if ($currentSort === 'ASC') {
            $sortIcon = ' <i class="fa-solid fa-sort-up"></i>';
        } elseif ($currentSort === 'DESC') {
            $sortIcon = ' <i class="fa-solid fa-sort-down"></i>';
        } else {
            $sortIcon = ' <i class="fa-solid fa-sort"></i>';
        }

        return '<a href="' . $url . '" class="text-decoration-none">' . $columnName . $sortIcon . '</a>';
    }

    /**
     * Mappa gli indici delle colonne ai nomi delle colonne del database
     */
    private function getColumnMapping() {
        // Se è stata impostata una mappatura personalizzata, usala
        if (!empty($this->columnMapping)) {
            return $this->columnMapping;
        }
        
        // Altrimenti usa la mappatura di default
        return [
            0 => 'firstname',  // Nome
            1 => 'familyname', // Cognome
            2 => 'email',      // Email
            3 => 'admin',      // Admin
            4 => 'category',   // Categoria
            5 => 'paymenttype', // Metodo di pagamento
            6 => 'paymentdate', // Data pagamento
            7 => 'amount',     // Importo
            8 => 'description' // Note
        ];
    }

    /**
     * Imposta la mappatura delle colonne per questa tabella
     */
    public function setColumnMapping($mapping) {
        $this->columnMapping = $mapping;
    }

    public function createPaginationLinks($baseUrl = '') {
        $info = $this->getPaginationInfo();
        
        if ($info['totalPages'] <= 1) {
            return '';
        }

        $html = '<nav aria-label="Paginazione">';
        $html .= '<ul class="pagination justify-content-center">';

        // Link "Precedente"
        if ($info['currentPage'] > 1) {
            $prevUrl = $this->buildUrl(['page' => $info['currentPage'] - 1]);
            $html .= '<li class="page-item"><a class="page-link" href="' . $prevUrl . '">Precedente</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Precedente</span></li>';
        }

        // Numeri delle pagine
        $startPage = max(1, $info['currentPage'] - 2);
        $endPage = min($info['totalPages'], $info['currentPage'] + 2);

        if ($startPage > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl(['page' => 1]) . '">1</a></li>';
            if ($startPage > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($i == $info['currentPage']) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl(['page' => $i]) . '">' . $i . '</a></li>';
            }
        }

        if ($endPage < $info['totalPages']) {
            if ($endPage < $info['totalPages'] - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= '<li class="page-item"><a class="page-link" href="' . $this->buildUrl(['page' => $info['totalPages']]) . '">' . $info['totalPages'] . '</a></li>';
        }

        // Link "Successivo"
        if ($info['currentPage'] < $info['totalPages']) {
            $nextUrl = $this->buildUrl(['page' => $info['currentPage'] + 1]);
            $html .= '<li class="page-item"><a class="page-link" href="' . $nextUrl . '">Successivo</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Successivo</span></li>';
        }

        $html .= '</ul>';
        $html .= '</nav>';

        return $html;
    }

    private function buildUrl($params = []) {
        $currentParams = $_GET;
        
        // Mantieni i parametri esistenti
        foreach ($params as $key => $value) {
            $currentParams[$key] = $value;
        }

        // Rimuovi parametri vuoti
        $currentParams = array_filter($currentParams, function($value) {
            return $value !== '' && $value !== null;
        });

        // Mantieni sempre il parametro 'page' per evitare 404
        if (!isset($currentParams['page'])) {
            $currentParams['page'] = 1;
        }

        return '?' . http_build_query($currentParams);
    }

    public function getItemsPerPageSelector() {
        $options = [5, 10, 25, 50];
        $currentItemsPerPage = $this->itemsPerPage;

        $html = '<div class="d-flex align-items-center">';
        $html .= '<label for="itemsPerPage" class="me-2">Elementi per pagina:</label>';
        $html .= '<select id="itemsPerPage" class="form-select form-select-sm" style="width: auto;" onchange="changeItemsPerPage(this.value)">';
        
        foreach ($options as $option) {
            $selected = ($option == $currentItemsPerPage) ? 'selected' : '';
            $html .= '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
        }
        
        $html .= '</select>';
        $html .= '</div>';

        return $html;
    }

    public function getPaginationSummary() {
        $info = $this->getPaginationInfo();
        
        if ($info['totalItems'] == 0) {
            return 'Nessun elemento trovato';
        }

        return 'Mostrando ' . $info['startItem'] . ' - ' . $info['endItem'] . ' di ' . $info['totalItems'] . ' elementi';
    }
} 