<?php
// se già effettuato login rimanda alla dashboard
if (!empty($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 1) {
    header("Location: index.php?page=dashboard");
    exit();
}

require("./auth/controller/AuthController.class.php");
$auth = new AuthController($_POST);
$auth->login();
?>
<?= Component::createTitle('Login') ?>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <?= Component::createInputText('email', 'Email', '', 'Inserisci la tua email', true) ?>
            <?= Component::createInputText('password', 'Password', '', 'Inserisci la tua password', true, 'password') ?>
            <?= Component::createSubmitButton('Accedi', 'primary') ?>
        </form>
        <a href="index.php?page=forgot">Password dimenticata?</a>   
    </div>
    <div class="col-md-3">
    </div>
</div>