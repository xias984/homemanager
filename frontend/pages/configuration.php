<?php
$configSite = new ConfigurationController();
$configData = $configSite->selectConfiguration();
if ((!empty($_POST) && isset($_POST)) || (!empty($_FILES['logo']) && isset($_FILES['logo']))) {
    $configStoreData = $configSite->updateConfigInfo($_POST, $_FILES['logo']);
}

?>
<?= Component::createTitle('Configurazione Sito') ?>
<div>&nbsp;</div>
<form class="row g-3" action="" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-3">
            <div class="m-3">
                <label for="logo" class="form-label">
                    <img src="<?= $configData["logo"] ? $configData['logo'] : 'https://placehold.co/200'; ?>" class="img-thumbnail" alt="logo">
                </label>
                <input class="file-input" type="file" id="logo" name="logo" accept="image/*">
            </div>
        </div>
        <div class="col-md-6">
            <form action="" method="post">
                <div class="form-group">
                    <label for="namesite">Modifica nome sito:</label>
                    <input type="text" class="form-control" name="namesite" value="<?=$configData['name']?>">
                </div>
                <div class="form-group">
                    <label for="descriptionsite">Modifica descrizione sito:</label>
                    <input type="text" class="form-control" name="descriptionsite" value="<?=$configData['description']?>">
                </div>
                <?= $configSite->viewThemePicker() ?>
                <button type="submit" class="btn btn-primary">Salva</button>
            </form>
        </div>
        <div class="col-md-3">
        </div>
    </div>
</form>