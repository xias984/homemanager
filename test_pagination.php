<?php
// Test della paginazione
require_once("./system/connection.php");
require_once("./system/DatabasePagination.class.php");

echo "<h1>Test Paginazione Database</h1>";

// Test 1: Paginazione utenti
echo "<h2>Test 1: Paginazione Utenti</h2>";
$pagination = new DatabasePagination('users', 'id', 'firstname', 'ASC');

$result = $pagination->getPaginatedData([
    'page' => 1,
    'itemsPerPage' => 2,
    'sortColumn' => 'firstname',
    'sortDirection' => 'ASC'
]);

echo "<pre>";
print_r($result);
echo "</pre>";

// Test 2: Paginazione categorie
echo "<h2>Test 2: Paginazione Categorie</h2>";
$pagination2 = new DatabasePagination('finance_category', 'id', 'category', 'ASC');

$result2 = $pagination2->getPaginatedData([
    'page' => 1,
    'itemsPerPage' => 3,
    'sortColumn' => 'category',
    'sortDirection' => 'ASC'
]);

echo "<pre>";
print_r($result2);
echo "</pre>";

// Test 3: Verifica tabella finance_paymenttype
echo "<h2>Test 3: Verifica Tabella finance_paymenttype</h2>";
$query = "SHOW TABLES LIKE 'finance_paymenttype'";
$result3 = mysqli_query($conn, $query);

if ($result3 && mysqli_num_rows($result3) > 0) {
    echo "✅ Tabella finance_paymenttype esiste<br>";
    
    // Conta i record
    $countQuery = "SELECT COUNT(*) as total FROM finance_paymenttype";
    $countResult = mysqli_query($conn, $countQuery);
    $count = mysqli_fetch_assoc($countResult)['total'];
    echo "📊 Record nella tabella: $count<br>";
    
    // Mostra i dati
    $dataQuery = "SELECT * FROM finance_paymenttype";
    $dataResult = mysqli_query($conn, $dataQuery);
    
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Payment Type</th><th>User ID</th><th>Data Inserimento</th></tr>";
    while ($row = mysqli_fetch_assoc($dataResult)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['paymenttype'] . "</td>";
        echo "<td>" . $row['iduser'] . "</td>";
        echo "<td>" . $row['datainserimento'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "❌ Tabella finance_paymenttype NON esiste<br>";
    echo "Eseguire lo script: create_missing_tables.sql<br>";
}

// Test 4: Verifica parametri URL
echo "<h2>Test 4: Parametri URL</h2>";
echo "GET parameters: <pre>" . print_r($_GET, true) . "</pre>";
?> 