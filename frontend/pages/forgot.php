<?php
require("./auth/controller/AuthController.class.php");
$auth = new AuthController($_POST);
$auth->resetPassword();
?>
<?= Component::createTitle('Ricorda Password') ?>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <div class="form-group">
                <label for="email">Inserisci l'email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            <button type="submit" class="btn btn-primary">Richiedi nuova password</button>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>