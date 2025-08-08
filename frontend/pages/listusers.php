<?php
require("./auth/controller/AuthController.class.php");
require("./system/TableHelper.class.php");

$users = new AuthController($_GET);

// Parametri per la paginazione lato database
$paginationParams = [
    'page' => isset($_GET['page']) ? (int)$_GET['page'] : 1,
    'itemsPerPage' => isset($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 10,
    'sortColumn' => $_GET['sort'] ?? 'firstname',
    'sortDirection' => $_GET['direction'] ?? 'ASC'
];

// Ottieni dati con paginazione lato database
$result = $users->listUserTablePaginated($paginationParams);
$userData = $result['data'];

// Inizializza TableHelper per paginazione e ordinamento
$tableHelper = TableHelper::createWithDatabasePagination($result['pagination'], $paginationParams['itemsPerPage']);
$tableHelper->setData($userData);

// Imposta la mappatura delle colonne per la tabella utenti
$tableHelper->setColumnMapping([
    0 => 'firstname',  // Nome
    1 => 'familyname', // Cognome
    2 => 'email',      // Email
    3 => 'admin',      // Admin
    4 => 'id'          // Actions (ID)
]);

$paginatedData = $tableHelper->getPaginatedData();

if (!empty($_GET['deleteid']) && isset($_GET['deleteid'])) {
    $users->removeUser();
} else if (!empty($_GET['editid']) && isset($_GET['editid'])) {
    if (!empty($_POST) && isset($_POST)) {
        $users->editUser($_POST);
    }
}
?>
<?= Component::createTitle('Lista utenti') ?>

<!-- Controlli paginazione -->
<div class="row mb-3">
    <div class="col-md-6">
        <?= $tableHelper->getPaginationSummary() ?>
    </div>
    <div class="col-md-6 text-end">
        <?= $tableHelper->getItemsPerPageSelector() ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12" style="overflow-x: auto;">
        <table class="table responsive">
            <thead>
                <tr>
                <?php foreach ($paginatedData[0] as $key => $header) { ?>
                    <th scope="col"><?= $tableHelper->createSortableHeader($key, $header) ?></th>
                <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($paginatedData, 1) as $user) {
                if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                    echo '<form action="" method="post" id="edituser">';
                }    
                ?>
                <tr>
                    <?php foreach ($user as $key => $value) {
                        $name = $key."-".$user[4];
                        //apro la cella
                        echo '<td>';
                        //attribuisco il valore per ogni cella
                        if ($key === 0 || $key === 1 || $key === 2) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                                //Component::createInputText($key, '', $value, '', false, 'text');
                                echo '<input type="text" value="' . $value . '" name="' . $key . '" id="' . $key . '">';
                            } else {
                                echo $value;
                            }
                        } elseif ($key === 3) {
                            $checked = $value ? 'checked' : '';
                            $disabled = !empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4] ?: 'disabled';
                            echo '<input class="form-check-input" type="checkbox" name="' . $key . '" ' . $checked . ' ' . $disabled . '>';
                        } elseif ($key === 4) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                                echo '<a href="#" onclick="document.getElementById(\'edituser\').submit();"><i class="fa-solid fa-check"></i></a> ' .
                                    '<a href="'.refreshPage().'"><i class="fa-solid fa-xmark"></i></a>';
                            } else {
                                echo '<a href="index.php?page=listusers&editid=' . $value . '"><i class="fa-regular fa-pen-to-square"></i></a> ' .
                                    '<a href="index.php?page=listusers&deleteid=' . $value . '"><i class="fa-solid fa-trash-can"></i></a>';
                            }
                        } else {
                            echo $value;
                        }
                        echo '</td>';
                        
                    }
                    ?>
                </tr>
                <?php 
                if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                    echo '</form>';
                }
            } ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Paginazione -->
<div class="row mt-3">
    <div class="col-md-12">
        <?= $tableHelper->createPaginationLinks() ?>
    </div>
</div>