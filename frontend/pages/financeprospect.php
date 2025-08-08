<?php
require("./finance/controller/FinanceController.class.php");
require("./system/TableHelper.class.php");
global $monthsList;
global $colors;

if (isset($_POST['reset'])) {
    $_POST = array();
}

$finances = new FinanceController();

// Genera sempre tutti i mesi disponibili dal database (senza filtri)
$allFinances = $finances->selectFinances();
$monthsWithNames = createMonthsWithYear($allFinances, 'paymentdate');

// Parametri per la paginazione lato database
$paginationParams = [
    'page' => $_GET['page'] ?? 1,
    'itemsPerPage' => $_GET['itemsPerPage'] ?? 15,
    'sortColumn' => $_GET['sort'] ?? 'paymentdate',
    'sortDirection' => $_GET['direction'] ?? 'DESC'
];

// Ottieni dati con paginazione lato database
$result = $finances->selectFinancesPaginated($_POST, $paginationParams);
$financesArray = $result['data'];
$categories = $finances->selectCategories();
$paymenttypes = $finances->selectPaymentTypes();

// Prepara i dati per la tabella con paginazione
$tableData = [
    ['Data pagamento', 'Tipo', 'Categoria', 'Modalità di pagamento', 'Importo', 'Note', 'Actions']
];

// Mappa per mantenere i dati originali
$originalDataMap = [];

if ($financesArray && is_array($financesArray)) {
    foreach ($financesArray as $financeValue) {
        $tableData[] = [
            date('d/m/Y', strtotime($financeValue['paymentdate'])),
            $financeValue['type'] == 'E' ? 'Entrata' : 'Uscita',
            $financeValue['category'],
            $financeValue['paymenttype'],
            $financeValue['amount'] . ' €',
            $financeValue['description'],
            $financeValue['id'] // ID per le azioni
        ];
        $originalDataMap[$financeValue['id']] = $financeValue;
    }
}

// Inizializza TableHelper per paginazione e ordinamento
$tableHelper = TableHelper::createWithDatabasePagination($result['pagination'], $paginationParams['itemsPerPage']);
$tableHelper->setData($tableData);
$paginatedData = $tableHelper->getPaginatedData();

$amountTot = [
    'E' => ['done' => 0, 'notdone' => 0],
    'U' => ['done' => 0, 'notdone' => 0]
];
foreach ($financesArray as $value) {
    if ($value['payed'] == 1) {
        $amountTot[$value['type']]['done'] += $value['amount'];
    } else {
        $amountTot[$value['type']]['notdone'] += $value['amount'];
    }
}

// Calcola i totali
$totalEntrate = $amountTot['E']['done'] + $amountTot['E']['notdone'];
$totalUscite = $amountTot['U']['done'] + $amountTot['U']['notdone'];
$totalPagato = $amountTot['E']['done'] - $amountTot['U']['done'];
$totalNonPagato = $amountTot['E']['notdone'] - $amountTot['U']['notdone'];
$totalComplessivo = $totalEntrate - $totalUscite;

// Gestione edit inline
if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction'])) {
    if (!empty($_POST) && isset($_POST)) {
        $finances->updateTransaction($_GET['edittransaction'], $_POST);
    }
}

if (isset($_GET['deletetransaction']) && !empty($_GET['deletetransaction'])) {
    $finances->deleteTransaction($_GET['deletetransaction']);
}

if (isset($_GET['payid']) && !empty($_GET['payid'])) {
    $finances->payTransaction($_GET['payid']);
}
?>

<?= Component::createTitle('Prospetto Entrate/Uscite') ?>

<div class="row">
    <div class="col-md-12" style="text-align:center">
        <h5>Filtri ricerca</h5>
        <form action="" method="post" id="filterform">
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-10">
                    <?= Component::createInputText('searchbox', '', '', isset($_POST['searchbox']) && !empty($_POST['searchbox']) ? $_POST['searchbox'] : 'Cerca nelle note...') ?>
                </div>
                <div class="col-md-2" style="text-align:left">
                    <div class="form-group">
                        <input class="form-check-input" type="checkbox" name="payed" <?= isset($_POST['payed']) ? 'checked' : '' ?>> Pagato<br>
                        <input class="form-check-input" type="checkbox" name="notpayed" <?= isset($_POST['notpayed']) ? 'checked' : '' ?>> Non Pagato
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6">
                    <?= Component::createInputSelect('paymenttypeid[]', 'Modalità di pagamento', $paymenttypes, false, true) ?>
                </div>
                <div class="col-md-6">
                    <?= Component::createInputSelect('categoryid[]', 'Categoria', $categories, false, true) ?>
                </div>
            </div>
        </div>
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6">
                    <?= Component::createInputSelect('types[]', 'Entrate/Uscite', ['E' => 'Entrate', 'U' => 'Uscite'], false, true) ?>
                </div>
                <div class="col-md-6">
                    <?= Component::createInputSelect('periodo[]', 'Periodo', $monthsWithNames, false, true, false, true) ?>
                </div>
            </div>
        </div>

        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" class="form-control btn btn-primary" value="CERCA" style="width:30%;float:right">
                </div>
                <div class="col-md-6">
                    <input type="submit" class="form-control" name="reset" value="RESET" style="width:30%;float:left">
                </div>
            </div>
        </div>
        </form>
    </div>
</div>

<div>&nbsp;</div>

<!-- Controlli paginazione -->
<div class="row mb-3">
    <div class="col-md-6">
        <?= $tableHelper->getPaginationSummary() ?>
    </div>
    <div class="col-md-6 text-end">
        <?= $tableHelper->getItemsPerPageSelector() ?>
    </div>
</div>

<div class="col-md-12" id="table-container" style="text-align:center; overflow-x: auto;">
    <table class="table responsive">
        <thead>
            <tr>
                <?php foreach ($paginatedData[0] as $key => $header) { ?>
                    <th scope="col"><?= $tableHelper->createSortableHeader($key, $header) ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach(array_slice($paginatedData, 1) as $row) { 
                $financeValue = $originalDataMap[$row[6]]; // Usa l'ID dalla riga paginata per ottenere i dati originali
                if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) {
                    echo '<form action="" method="post" id="edittransaction">';
                }
            ?>
            <tr <?php if (empty($financeValue['payed'])) { echo " style=\"color: " . $colors['subtitle'] . "\""; } ?>>
                <td>
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <input type="date" name="paymentdate" value="<?= date('Y-m-d', strtotime($financeValue['paymentdate'])) ?>" class="form-control">
                    <?php } else { ?>
                        <?= date('d/m/Y', strtotime($financeValue['paymentdate'])) ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <select name="type" class="form-control">
                            <option value="E" <?= $financeValue['type'] == 'E' ? 'selected' : '' ?>>Entrata</option>
                            <option value="U" <?= $financeValue['type'] == 'U' ? 'selected' : '' ?>>Uscita</option>
                        </select>
                    <?php } else { ?>
                        <?= $financeValue['type'] == 'E' ? 'Entrata' : 'Uscita' ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <select name="categoryid" class="form-control">
                            <?php foreach ($categories as $catId => $catName) { ?>
                                <option value="<?= $catId ?>" <?= $financeValue['category'] == $catName ? 'selected' : '' ?>>
                                    <?= $catName ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <?= $financeValue['category'] ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <select name="paymenttypeid" class="form-control">
                            <?php foreach ($paymenttypes as $payId => $payName) { ?>
                                <option value="<?= $payId ?>" <?= $financeValue['paymenttype'] == $payName ? 'selected' : '' ?>>
                                    <?= $payName ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } else { ?>
                        <?= $financeValue['paymenttype'] ?>
                    <?php } ?>
                </td>
                <td style="text-align:right">
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <input type="number" name="amount" value="<?= $financeValue['amount'] ?>" class="form-control" step="0.01">
                    <?php } else { ?>
                        <span style="color:<?= $financeValue['payed'] ? $financeValue['type'] == 'U' ? 'red' : 'lightgreen' : $colors['subtitle']?>">
                            <?= $financeValue['amount'] ?> €
                        </span>
                    <?php } ?>
                </td>
                <td>
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <input type="text" name="description" value="<?= htmlspecialchars($financeValue['description']) ?>" class="form-control">
                    <?php } else { ?>
                        <?= $financeValue['description'] ?>
                    <?php } ?>
                </td>
                <td>
                    <?php if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) { ?>
                        <a href="#" onclick="document.getElementById('edittransaction').submit();">
                            <i class="fa-solid fa-check"></i>
                        </a>&nbsp;
                        <a href="<?= refreshPage() ?>#table-container">
                            <i class="fa-solid fa-xmark"></i>
                        </a>
                    <?php } else { ?>
                        <a href="<?=refreshPage()?>&payid=<?=$financeValue['id']?>#table-container" alt="Paga">
                            <i class="fa-solid fa-cash-register"></i>
                        </a>&nbsp;
                        <a href="<?=refreshPage()?>&edittransaction=<?=$financeValue['id']?>#table-container">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </a>&nbsp;
                        <a href="<?=refreshPage()?>&deletetransaction=<?=$financeValue['id']?>#table-container">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    <?php } ?>
                </td>
            </tr>
            <?php 
                if (!empty($_GET['edittransaction']) && isset($_GET['edittransaction']) && $_GET['edittransaction'] == $financeValue['id']) {
                    echo '</form>';
                }
            } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6">Totale Pagato</th>
                <th scope="col" style="color: <?= $totalPagato >= 0 ? 'lightgreen' : 'red' ?>">
                    <?= number_format($totalPagato, 2) ?>€
                </th>
            </tr>
            <tr>
                <th colspan="6">Totale Non Pagato</th>
                <th scope="col" style="color: <?= $colors['subtitle'] ?>">
                    <?= number_format($totalNonPagato, 2) ?>€
                </th>
            </tr>
            <tr>
                <th colspan="6"><strong>Totale Complessivo</strong></th>
                <th scope="col" style="color: <?= $totalComplessivo >= 0 ? 'lightgreen' : 'red' ?>; font-weight: bold;">
                    <?= number_format($totalComplessivo, 2) ?>€
                </th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Paginazione -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <?= $tableHelper->createPaginationLinks() ?>
    </div>
</div>
