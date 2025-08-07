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
            <?= Component::createInputText('email', 'Inserisci l\'email:', '', 'Inserisci la tua email', true) ?>
            <?= Component::createSubmitButton('Richiedi nuova password', 'primary') ?>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>