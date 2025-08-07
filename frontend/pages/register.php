<?php
require("./auth/controller/AuthController.class.php");
$auth = new AuthController($_POST);
$auth->register();
?>
<?= Component::createTitle('Registrazione') ?>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <?= Component::createInputText('firstname', 'Nome', 'Inserisci il tuo nome', true) ?>
            <?= Component::createInputText('familyname', 'Cognome', 'Inserisci il tuo cognome', true) ?>
            <?= Component::createInputText('email', 'Email', 'Inserisci la tua email', true) ?>
            <?= Component::createInputText('password', 'Password', '', 'Inserisci la tua password', true, 'password') ?>
            <?= Component::createInputText('password2', 'Conferma Password', '', 'Conferma la tua password', true, 'password') ?>
            <?= Component::createSubmitButton('Registra', 'primary') ?>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>