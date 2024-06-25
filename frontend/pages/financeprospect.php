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
if (isset($_GET['edittransaction'])) {
    $editTransaction = $finances->editTransaction($_GET['edittransaction']);
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
<div class="col-md-12" style="text-align:center; overflow-x: auto;">
    <table class="table responsive">
        <thead>
            <tr>
                <th scope="col">Data pagamento</th>
                <th scope="col">Categoria</th>
                <th scope="col">Modalità di pagamento</th>
                <th scope="col">Importo</th>
                <th scope="col">Note:</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($financesArray as $financeValue) { ?>
            <tr <?php if (empty($financeValue['payed'])) { echo " style=\"color: " . $colors['subtitle'] . "\""; } ?>>
                <td><?= date('d/m/Y', strtotime($financeValue['paymentdate'])) ?></td>
                <td><?= $financeValue['category'] ?></td>
                <td><?= $financeValue['paymenttype'] ?></td>
                <td style="color:<?= $financeValue['type'] == 'U' ? 'red' : 'green' ?>; text-align:right"><strong><?= $financeValue['amount'] ?> €</strong></td>
                <td><?= $financeValue['description'] ?></td>
                <td style="text-align:center; text-size: 6px">
                    <?php if ($financeValue['payed'] == 0) { echo '<a href="'.refreshPage().'&payid=' . $financeValue['id'] . '">Pagato</a><br>'; }?>
                    <a href="<?=refreshPage()?>&edittransaction=<?=$financeValue['id']?>">Modifica</a><br>
                    <a href="<?=refreshPage()?>&deletetransaction=<?=$financeValue['id']?>">Cancella</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Totale</th>
                <th scope="col"><?= ($amountTot['E'] - $amountTot['U']) ?>€</th>
            </tr>
        </tfoot>
    </table>
</div>
