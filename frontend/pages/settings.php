<?php
if (!empty($_POST) && isset($_POST)) {
    require("./auth/controller/AuthController.class.php");
    $auth = new AuthController($_POST);
    $auth->verifyPasswordDB();
}
?>
<?= Component::createTitle('Modifica Password') ?>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <div class="form-group">
                <label for="oldpassword">Inserisci vecchia password:</label>
                <input type="password" class="form-control" name="oldpassword" placeholder="Inserisci vecchia password" required>
            </div>
            <div class="form-group">
                <label for="newpassword">Inserisci nuova password:</label>
                <input type="password" class="form-control" name="newpassword" placeholder="Inserisi nuova password" required>
            </div>
            <div class="form-group">
                <label for="newpassword2">Conferma nuova password:</label>
                <input type="password" class="form-control" name="newpassword2" placeholder="Conferma nuova password" required>
            </div>
            <button type="submit" class="btn btn-primary">Modifica password</button>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>