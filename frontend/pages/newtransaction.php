<?php
require("./finance/controller/FinanceController.class.php");

$finance = new FinanceController();
$categories = $finance->selectCategories();

if (!empty($_POST)) {
    $finance->registerAmount($_POST);
}
?>

<div class="title">
    <h3>Registra Transazione</h3>
</div>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <input type="hidden" name="iduser" value="<?=$_SESSION['iduser']?>">
            <div class="form-group">
                <label for="typeamount">Tipo importo:</label>
                <select class="form-control" name="typeamount" style="flex:1;">
                    <option value="E">Entrata</option>
                    <option value="U">Uscita</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Importo:</label>
                <input type="number" class="form-control" name="amount" placeholder="0" required>
            </div>
            <div class="form-group">
                <label for="description">Descrizione:</label>
                <input type="text" class="form-control" name="description" required>
            </div>
            <div class="form-group">
                <label for="categoryid">Categoria:</label>
                <select class="form-control" name="categoryid" style="flex:1;">
                    <?php foreach($categories as $category) { dump($category);?>
                    <option value="<?=$category['id']?>"><?=$category['category']?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="data">Data di pagamento:</label>
                <input type="date" class="form-control" name="paymentdate">
            </div>
            <button type="submit" class="btn btn-primary">Accedi</button>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>