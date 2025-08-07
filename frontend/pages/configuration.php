<?php
$configSite = new ConfigurationController();
$configData = $configSite->selectConfiguration();
if ((!empty($_POST) && isset($_POST)) || (!empty($_FILES['logo']) && isset($_FILES['logo']))) {
    $configStoreData = $configSite->updateConfigInfo($_POST, $_FILES['logo']);
}

?>
<?= Component::createTitle('Configurazione Sito') ?>
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
                <?= Component::createInputText('namesite', 'Modifica nome sito:', $configData['name']) ?>
                <?= Component::createInputText('descriptionsite', 'Modifica descrizione sito:', $configData['description']) ?>
                <?= $configSite->viewThemePicker() ?>
                <?= Component::createSubmitButton('Salva', 'primary') ?>
            </form>
        </div>
        <div class="col-md-3">
        </div>
    </div>
</form>