<?php
require("./finance/controller/FinanceController.class.php");
global $monthsList;
global $colors;

if (isset($_POST['reset'])) {
    $_POST = array();
}

$finances = new FinanceController();
$financesArray = $finances->selectFinances($_POST);

$monthArray = [];
foreach ($finances->selectFinances() as $financesValue) {
    $monthArray[] = date('m', strtotime($financesValue['paymentdate']));
}
$months = array_unique($monthArray);
$categories = $finances->selectCategories();
$paymenttypes = $finances->selectPaymentTypes();

$amountTot = [
    'E' => 0,
    'U' => 0
];
foreach ($financesArray as $value) {
    $amountTot[$value['type']] += $value['amount'];
}

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
                    <?= Component::createInputSelect('periodo[]', 'Periodo', $months, false, true, false, true) ?>
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

<div class="col-md-12" id="table-container" style="text-align:center; overflow-x: auto;">
    <table class="table responsive">
        <thead>
            <tr>
                <th scope="col">Data pagamento</th>
                <th scope="col">Tipo</th>
                <th scope="col">Categoria</th>
                <th scope="col">Modalità di pagamento</th>
                <th scope="col">Importo</th>
                <th scope="col">Note</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($financesArray as $financeValue) { 
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
                <th colspan="6">Totale</th>
                <th scope="col"><?= ($amountTot['E'] - $amountTot['U']) ?>€</th>
            </tr>
        </tfoot>
    </table>
</div>
