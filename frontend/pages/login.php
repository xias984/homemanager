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
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required>
            </div>
            <button type="submit" class="btn btn-primary">Accedi</button>
        </form>
        <a href="index.php?page=forgot">Password dimenticata?</a>   
    </div>
    <div class="col-md-3">
    </div>
</div>