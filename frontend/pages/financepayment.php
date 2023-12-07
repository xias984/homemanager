<?php
require("./finance/controller/FinanceController.class.php");

$paymenttype = new FinanceController();
$paymenttypeData = $paymenttype->listPaymentTypeTable();

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
<div class="title">
    <h3>Metodi di pagamento</h3>
</div>

<form action="" method="post">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <input type="text" name="paymenttype" class="form-control" placeholder="Aggiungi metodo di pagamento"
                    required>
            </div>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Inserisci</button>
        </div>
    </div>
</form>

<div class="row">&nbsp;</div>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8" style="overflow-x: auto;">
        <table class="table responsive" style="text-align:center">
            <thead>
                <tr>
                    <?php foreach ($paymenttypeData[0] as $header) { ?>
                    <th scope="col"><?=$header?></th>
                    <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($paymenttypeData, 1) as $paymenttype):
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
                                echo '<a href="#" onclick="document.getElementById(\'editpaymenttype\').submit();">Conferma</a> - ' .
                                    '<a href="'.refreshPage().'">Chiudi</a>';
                            } else {
                                echo '<a href="index.php?page=financepayment&editid=' . $value . '">Modifica</a> - 
                                <a href="index.php?page=financepayment&deleteid=' . $value . '">Cancella</a>';
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