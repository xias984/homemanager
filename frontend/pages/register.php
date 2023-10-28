<?php
require("./auth/controller/AuthController.class.php");
$auth = new AuthController($_POST);
$auth->register();
?>
<div class="title">
    <h3>Registrazione</h3>
</div>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <div class="form-group">
                <label for="firstname">Nome:</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Inserisci il tuo username" required>
            </div>
            <div class="form-group">
                <label for="familyname">Cognome:</label>
                <input type="text" class="form-control" id="familyname" name="familyname" placeholder="Inserisci il tuo cognome" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la tua password" required>
            </div>
            <div class="form-group">
                <label for="password2">Conferma Password:</label>
                <input type="password" class="form-control" id="password2" name="password2" placeholder="Conferma la tua password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iscriviti</button>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>