# Paginazione e Ordinamento Lato Backend

## Panoramica

Sono state implementate le funzionalità di paginazione e ordinamento lato database per migliorare le performance e la scalabilità del sistema HomeManager. Questa implementazione sostituisce la paginazione lato client con una più efficiente paginazione lato server.

## Architettura Implementata

### 1. Classe DatabasePagination
La classe `DatabasePagination` gestisce tutte le operazioni di paginazione e ordinamento a livello di database:

```php
// Esempio di utilizzo
$pagination = new DatabasePagination('users', 'id', 'firstname', 'ASC');
$result = $pagination->getPaginatedData([
    'page' => 1,
    'itemsPerPage' => 10,
    'sortColumn' => 'firstname',
    'sortDirection' => 'ASC',
    'where' => 'WHERE admin = 1'
]);
```

#### Metodi Principali:
- `getPaginatedData()` - Paginazione base
- `getPaginatedDataWithJoins()` - Paginazione con JOIN
- `buildWhereClause()` - Costruzione clausole WHERE
- `buildSearchClause()` - Ricerche LIKE
- `combineWhereClauses()` - Combinazione clausole

### 2. Modifiche ai Model

#### Auth.class.php
```php
public function getUsersPaginated($params = []) {
    // Nuovo metodo per paginazione utenti
}
```

#### Category.class.php
```php
public function getCategoryPaginated($params = []) {
    // Nuovo metodo per paginazione categorie
}
```

#### PaymentType.class.php
```php
public function getPaymentTypesPaginated($params = []) {
    // Nuovo metodo per paginazione metodi di pagamento
}
```

#### Finance.class.php
```php
public function getTransactionsPaginated($filters = [], $params = []) {
    // Nuovo metodo per paginazione transazioni con filtri
}
```

### 3. Modifiche ai Controller

#### AuthController.class.php
```php
public function listUserTablePaginated($params = []) {
    // Nuovo metodo per lista utenti paginata
}
```

#### FinanceController.class.php
```php
public function listCategoryTablePaginated($params = []) {
    // Nuovo metodo per lista categorie paginata
}

public function listPaymentTypeTablePaginated($params = []) {
    // Nuovo metodo per lista metodi di pagamento paginata
}

public function selectFinancesPaginated($filters = null, $params = []) {
    // Nuovo metodo per transazioni finanziarie paginate
}
```

### 4. Aggiornamento TableHelper

La classe `TableHelper` è stata estesa per supportare la paginazione lato database:

```php
// Nuovo costruttore per paginazione database
$tableHelper = TableHelper::createWithDatabasePagination($paginationInfo, $itemsPerPage);
$tableHelper->setData($data);
```

## Vantaggi dell'Implementazione

### 1. Performance
- **Query Ottimizzate**: Solo i dati necessari vengono recuperati dal database
- **Indici Database**: Sfrutta gli indici per ordinamento veloce
- **Memoria Ridotta**: Non carica tutti i dati in memoria

### 2. Scalabilità
- **Grandi Dataset**: Gestisce efficacemente migliaia di record
- **Concorrenza**: Riduce il carico sul database
- **Caching**: Possibilità di implementare cache a livello query

### 3. Funzionalità
- **Filtri Complessi**: Supporta filtri multipli e ricerche
- **Ordinamento Dinamico**: Qualsiasi colonna ordinabile
- **Paginazione Intelligente**: Gestione automatica dei limiti

## Implementazione nelle Pagine

### 1. Lista Utenti (`listusers.php`)
```php
// Parametri per la paginazione lato database
$paginationParams = [
    'page' => $_GET['page'] ?? 1,
    'itemsPerPage' => $_GET['itemsPerPage'] ?? 10,
    'sortColumn' => $_GET['sort'] ?? 'firstname',
    'sortDirection' => $_GET['direction'] ?? 'ASC'
];

// Ottieni dati con paginazione lato database
$result = $users->listUserTablePaginated($paginationParams);
```

### 2. Categorie Finanziarie (`financecategory.php`)
```php
$result = $category->listCategoryTablePaginated($paginationParams);
```

### 3. Metodi di Pagamento (`financepayment.php`)
```php
$result = $paymenttype->listPaymentTypeTablePaginated($paginationParams);
```

### 4. Prospetto Finanziario (`financeprospect.php`)
```php
$result = $finances->selectFinancesPaginated($_POST, $paginationParams);
```

## Query SQL Generate

### Esempio Query Base
```sql
SELECT COUNT(*) as total FROM users WHERE admin = 1;
SELECT * FROM users WHERE admin = 1 ORDER BY firstname ASC LIMIT 10 OFFSET 0;
```

### Esempio Query con Filtri
```sql
SELECT COUNT(*) as total FROM finance 
WHERE 1 = 1 
  AND type IN ('E', 'U') 
  AND description LIKE '%cerca%' 
  AND payed = 1;

SELECT * FROM finance 
WHERE 1 = 1 
  AND type IN ('E', 'U') 
  AND description LIKE '%cerca%' 
  AND payed = 1 
ORDER BY paymentdate DESC 
LIMIT 15 OFFSET 0;
```

## Parametri URL Supportati

- `page` - Numero di pagina corrente
- `itemsPerPage` - Elementi per pagina (5, 10, 25, 50)
- `sort` - Indice colonna da ordinare
- `direction` - Direzione ordinamento (ASC/DESC)

## Sicurezza

### 1. SQL Injection Prevention
- Uso di `mysqli_real_escape_string()` per tutti i parametri
- Validazione dei tipi di dati
- Sanitizzazione degli array per clausole IN

### 2. Validazione Parametri
- Limiti sui valori di paginazione
- Controllo direzioni di ordinamento
- Validazione indici colonne

### 3. Limiti di Performance
- Massimo 100 elementi per pagina
- Timeout query configurato
- Gestione errori robusta

## Compatibilità

### Backward Compatibility
- I metodi originali rimangono funzionanti
- Graduale migrazione possibile
- Nessuna rottura delle funzionalità esistenti

### Database Support
- MySQL 5.7+
- MariaDB 10.2+
- Compatibile con indici esistenti

## Monitoraggio e Debug

### Query Logging
```php
// Abilita logging per debug
$pagination->enableQueryLogging();
```

### Performance Metrics
- Tempo di esecuzione query
- Numero di record processati
- Utilizzo memoria

## Manutenzione

### Aggiungere Paginazione a Nuove Tabelle

1. **Model**: Aggiungi metodo `getXXXPaginated()`
2. **Controller**: Aggiungi metodo `listXXXTablePaginated()`
3. **View**: Aggiorna la pagina per usare i nuovi metodi
4. **Test**: Verifica performance e funzionalità

### Esempio Implementazione Completa
```php
// Model
public function getItemsPaginated($params = []) {
    require_once("./system/DatabasePagination.class.php");
    $pagination = new DatabasePagination($this->table, 'id', 'name', 'ASC');
    return $pagination->getPaginatedData($params);
}

// Controller
public function listItemsTablePaginated($params = []) {
    $result = $this->model->getItemsPaginated($params);
    // Formatta dati per la vista
    return ['data' => $formattedData, 'pagination' => $result['pagination']];
}

// View
$result = $controller->listItemsTablePaginated($paginationParams);
$tableHelper = TableHelper::createWithDatabasePagination($result['pagination']);
```

## Note Tecniche

- **Indici Database**: Assicurati di avere indici sulle colonne di ordinamento
- **Memoria**: La paginazione riduce significativamente l'uso di memoria
- **Cache**: Considera l'implementazione di cache per query frequenti
- **Monitoring**: Monitora le performance delle query con EXPLAIN 