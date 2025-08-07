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
            <?= Component::createInputText('oldpassword', 'Inserisci vecchia password:', '', 'Inserisci vecchia password', true, 'password'); ?>
            <?= Component::createInputText('newpassword', 'Inserisci nuova password:', '', 'Inserisi nuova password', true, 'password'); ?>
            <?= Component::createInputText('newpassword2', 'Conferma nuova password:', '', 'Conferma nuova password', true, 'password'); ?>
            <?= Component::createSubmitButton('Modifica password', 'primary'); ?>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>