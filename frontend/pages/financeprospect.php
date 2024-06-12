<?php
require("./finance/controller/FinanceController.class.php");

$finances = new FinanceController();
$financesArray = $finances->selectFinances();
?>
<div class="title">
    <h3>Prospetto Entrate/Uscite</h3>
</div>
<div class="col-md-12" style="text-align:center">
    <table class="table responsive">
        <thead>
            <tr>
                <th scope="col">Data pagamento</th>
                <th scope="col">Inserito da:</th>
                <th scope="col">Categoria</th>
                <th scope="col">Modalità di pagamento</th>
                <th scope="col">Importo</th>
                <th scope="col">Note:</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($financesArray as $financeValue) { ?>
            <tr>
                <td><?= date('d/m/Y', strtotime($financeValue['paymentdate'])) ?></td>
                <td><?= $financeValue['user'] ?></td>
                <td><?= $financeValue['category'] ?></td>
                <td><?= $financeValue['paymenttype'] ?></td>
                <td style="color:<?= $financeValue['type'] == 'U' ? 'red' : 'green' ?>; text-align:right"><strong><?= $financeValue['amount'] ?> €</strong></td>
                <td><?= $financeValue['description'] ?></td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>