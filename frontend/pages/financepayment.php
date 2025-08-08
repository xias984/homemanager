<?php
require("./finance/controller/FinanceController.class.php");
require("./system/TableHelper.class.php");

$paymenttype = new FinanceController();

// Parametri per la paginazione lato database
$paginationParams = [
    'page' => $_GET['page'] ?? 1,
    'itemsPerPage' => $_GET['itemsPerPage'] ?? 10,
    'sortColumn' => $_GET['sort'] ?? 'paymenttype',
    'sortDirection' => $_GET['direction'] ?? 'ASC'
];

// Ottieni dati con paginazione lato database
$result = $paymenttype->listPaymentTypeTablePaginated($paginationParams);
$paymenttypeData = $result['data'];

// Inizializza TableHelper per paginazione e ordinamento
$tableHelper = TableHelper::createWithDatabasePagination($result['pagination'], $paginationParams['itemsPerPage']);
$tableHelper->setData($paymenttypeData);

// Imposta la mappatura delle colonne per la tabella metodi di pagamento
$tableHelper->setColumnMapping([
    0 => 'paymenttype',     // Metodo di pagamento
    1 => 'iduser',          // Inserito da
    2 => 'datainserimento', // Data inserimento
    3 => 'id'               // Actions (ID)
]);

$paginatedData = $tableHelper->getPaginatedData();

if (!empty($_POST['paymenttype'])) {
    $paymenttype->registerPaymentType($_POST['paymenttype']);
}

if (!empty($_GET['deleteid']) && isset($_GET['deleteid'])) {
    $paymenttype->removePaymentType($_GET['deleteid']);
} else if (!empty($_GET['editid'])) {
    if (!empty($_POST) && isset($_POST)) {
        $paymenttype->editPaymentType($_GET['editid'], $_POST);
    }
}
?>
<?= Component::createTitle('Metodi di pagamento') ?>

<form action="" method="post">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <?= Component::createInputText('paymenttype', '', '', 'Aggiungi metodo di pagamento', true) ?>
        </div>
        <div class="col-md-3">
            <?= Component::createSubmitButton('Inserisci', 'primary') ?>
        </div>
    </div>
</form>

<div class="row">&nbsp;</div>

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
    <div class="col-md-2"></div>
    <div class="col-md-8" style="overflow-x: auto;">
        <table class="table responsive" style="text-align:center">
            <thead>
                <tr>
                    <?php foreach ($paginatedData[0] as $key => $header) { ?>
                    <th scope="col"><?= $tableHelper->createSortableHeader($key, $header) ?></th>
                    <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($paginatedData, 1) as $paymenttype):
                    if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $paymenttype[3]) {
                        echo '<form action="" method="post" id="editpaymenttype">';
                    }
                ?>
                <tr>
                    <?php foreach ($paymenttype as $key => $value) {
                        echo '<td>';
                        if ($key == 0) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $paymenttype[3]) {
                                echo '<input type="text" value="' . $value . '" name="' . $key . '">';
                            } else {
                                echo '<strong>' . $value . '</strong>';
                            }
                        }else if ($key == 1 || $key == 2) {
                            echo $value;
                        } else if ($key == 3) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $paymenttype[3]) {
                                echo '<a href="#" onclick="document.getElementById(\'editpaymenttype\').submit();"><i class="fa-solid fa-check"></i></a> ' .
                                    '<a href="'.refreshPage().'"><i class="fa-solid fa-xmark"></i></a>';
                            } else {
                                echo '<a href="index.php?page=financepayment&editid=' . $value . '"><i class="fa-regular fa-pen-to-square"></i></a> 
                                <a href="index.php?page=financepayment&deleteid=' . $value . '"><i class="fa-solid fa-trash-can"></i></a>';
                            }
                        }
                        echo '</td>';
                    }?>
                </tr>
                <?php 
                if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $paymenttype[3]) {
                    echo '</form>';
                }
                endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-2"></div>
</div>

<!-- Paginazione -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <?= $tableHelper->createPaginationLinks() ?>
    </div>
</div>