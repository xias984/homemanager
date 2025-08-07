<?php
require("./finance/controller/FinanceController.class.php");

$finance = new FinanceController();
$categories = $finance->selectCategories();
$paymenttypes = $finance->selectPaymentTypes();
if (!empty($_POST)) {
    $finance->registerAmount($_POST);
}
?>
<?= Component::createTitle('Registra Transazione') ?>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <?= Component::createInputText('iduser', '', $_SESSION['iduser'], '', false, 'hidden'); ?>
            <?= Component::createInputSelect('typeamount', 'Tipo importo', ['E' => 'Entrate', 'U' => 'Uscite'], false) ?>
            <?= Component::createInputText('amount', 'Importo', '', '0', true, 'number'); ?>
            <?= Component::createCheckbox('installment', 'Rata', false, 'installmentCheckbox'); ?>
            <div id="installmentEndDateContainer" style="display: none;">
                <?= Component::createInputText('installmentenddate', 'Data di estinzione rata', '', '', true, 'date'); ?>
            </div>
            <?= Component::createInputText('description', 'Descrizione', '', ''); ?>
            <?= Component::createInputSelect('categoryid', 'Categoria', $categories, false); ?>
            <?= Component::createInputSelect('paymenttypeid', 'Modalità di pagamento', $paymenttypes, false); ?>
            <?= Component::createInputText('paymentdate', 'Data di pagamento', '', '', true, 'date'); ?>    
            <?= Component::createSubmitButton('Inserisci', 'secondary'); ?>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>