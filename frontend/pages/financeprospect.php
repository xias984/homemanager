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
?>
<div class="title">
    <h3>Prospetto Entrate/Uscite</h3>
</div>
<div class="row">
    <div class="col-md-12" style="text-align:center">
        <h5>Filtri ricerca</h5>
        <form action="" method="post">
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <input class="form-control" type="textbox" name="searchbox" placeholder="<?= isset($_POST['searchbox']) && !empty($_POST['searchbox']) ? $_POST['searchbox'] : 'Cerca nelle note...'?>">
                    </div>
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
                    <div class="form-group">
                        <label for="paymenttypes">Modalità di pagamento:</label>
                        <select class="form-control" name="paymenttypeid[]" style="flex:1;" multiple>
                            <?php foreach($paymenttypes as $paymenttype) {?>
                            <option value="<?=$paymenttype['id']?>" <?= isset($_POST['paymenttypeid']) && in_array($paymenttype['id'], $_POST['paymenttypeid']) ? 'selected' : '' ?>><?=$paymenttype['paymenttype']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="categoryid">Categoria:</label>
                        <select class="form-control" name="categoryid[]" style="flex:1;" multiple>
                            <?php foreach($categories as $category) {?>
                            <option value="<?=$category['id']?>" <?= isset($_POST['categoryid']) && in_array($category['id'], $_POST['categoryid']) ? 'selected' : '' ?>><?=$category['category']?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6"><div class="form-group">
                        <label for="types">Entrate/Uscite</label>
                        <select class="form-control" name="types[]" style="flex:1;" multiple>
                            <option value="E" <?= isset($_POST['types']) && in_array('E', $_POST['types']) ? 'selected' : '' ?>>Entrate</option>
                            <option value="U" <?= isset($_POST['types']) && in_array('U', $_POST['types']) ? 'selected' : '' ?>>Uscite</option>
                        </select>
                    </div></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="periodo">Periodo:</label>
                        <select class="form-control" name="periodo[]" style="flex:1;" multiple>
                            <?php foreach($months as $month) {?>
                            <option value="<?=$month?>"><?= $monthsList[$month]?></option>
                            <?php } ?>
                        </select>
                    </div>
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
<div class="col-md-12" style="text-align:center">
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
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
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
