<?php
require_once("password/controller/PasswordController.class.php");

$passwordManager = new PasswordController();

// Gestione inserimento nuovo servizio
if (isset($_POST['newservice'])) {
    if ($passwordManager->insertService($_POST)) {
        header("Location: index.php?page=passwordmanager&idmsg=37");
        exit;
    }
}

// Gestione aggiornamento servizio
if (isset($_POST['editservice'])) {
    if ($passwordManager->updateService($_POST)) {
        header("Location: index.php?page=passwordmanager&idmsg=38");
        exit;
    }
}

// Gestione eliminazione servizio
if (!empty($_GET['deleteservice'])) {
    if ($passwordManager->deleteService($_GET['deleteservice'])) {
        header("Location: index.php?page=passwordmanager&idmsg=39");
        exit;
    }
}

// Gestione inserimento nuova password
if (isset($_POST['newpassword'])) {
    if ($passwordManager->insertPassword($_POST)) {
        header("Location: index.php?page=passwordmanager&idmsg=40");
        exit;
    }
}

// Gestione aggiornamento password
if (isset($_POST['editpassword'])) {
    if ($passwordManager->updatePassword($_POST)) {
        header("Location: index.php?page=passwordmanager&idmsg=41");
        exit;
    }
}

// Gestione eliminazione password
if (!empty($_GET['deletepassword'])) {
    if ($passwordManager->deletePassword($_GET['deletepassword'])) {
        header("Location: index.php?page=passwordmanager&idmsg=42");
        exit;
    }
}

// Ottieni dati
$services = $passwordManager->getServices();
$passwordsGrouped = $passwordManager->getPasswordsGroupedByService();
?>

<?= Component::createTitle('Password Manager') ?>

<!-- Sezione per aggiungere nuovo servizio -->
<div class="row">
    <div class="col-md-12">
        <div class="chat-box p-3">
            <form class="custom-form" action="" method="post">
                <div class="message-column">
                    <div class="message">
                        <strong>Nuovo Servizio:</strong>
                    </div>
                    <div class="message-text">
                        <?= Component::createInputText('name', '', '', 'Nome servizio (es. Gmail, Facebook)', true) ?>
                        <?= Component::createInputText('description', '', '', 'Descrizione del servizio') ?>
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <?= Component::createSubmitButton('<i class="fa-solid fa-plus"></i>', 'primary', 'newservice') ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sezione per aggiungere nuova password -->
<div class="row">
    <div class="col-md-12">
        <div class="chat-box p-3">
            <form class="custom-form" action="" method="post">
                <div class="message-column">
                    <div class="message">
                        <strong>Nuova Password:</strong>
                    </div>
                    <div class="message-text">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <select name="service_id" id="service_id" class="form-control" required>
                                        <option value="">Seleziona servizio</option>
                                        <?php foreach ($services as $service): ?>
                                        <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <?= Component::createInputText('username', '', '', 'Username/Email', true) ?>
                            </div>
                            <div class="col-md-3">
                                <?= Component::createInputText('password', '', '', 'Password', true, 'password') ?>
                            </div>
                            <div class="col-md-3">
                                <?= Component::createInputText('notes', '', '', 'Note (opzionale)') ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <?= Component::createSubmitButton('<i class="fa-solid fa-key"></i>', 'primary', 'newpassword') ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Visualizzazione password raggruppate per servizio -->
<?php if (!empty($passwordsGrouped)): ?>
    <?php foreach ($passwordsGrouped as $serviceName => $passwords): ?>
    <div class="row">
        <div class="col-md-12 p-3">
            <div class="chat-box">
                <div class="message-column">
                    <div class="message">
                        <strong><i class="fa-solid fa-server"></i> <?= htmlspecialchars($serviceName) ?> (<?= count($passwords) ?> password)</strong>
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <a href="#" data-toggle="collapse" data-target="#service<?= md5($serviceName) ?>">
                            <i class="fa-solid fa-chevron-down"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="collapse" id="service<?= md5($serviceName) ?>">
                <?php foreach ($passwords as $password): ?>
                <div class="chat-box">
                    <?php if (!empty($_GET['editpassword']) && ($password['id'] == $_GET['editpassword'])) {
                        echo '<form class="custom-form" action="" method="post" id="editpassword">';
                    } ?>
                    <div class="message-column">
                        <div class="message">
                            <strong><?= htmlspecialchars($password['username']) ?></strong>
                            <?php if (!empty($password['email'])): ?>
                            <br><small class="text-muted"><?= htmlspecialchars($password['email']) ?></small>
                            <?php endif; ?>
                        </div>
                        <div class="message-text">
                            <?php if (!empty($_GET['editpassword']) && ($password['id'] == $_GET['editpassword'])): ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="service_id" id="edit_service_id" class="form-control" required>
                                                <?php foreach ($services as $service): ?>
                                                <option value="<?= $service['id'] ?>" <?= ($service['id'] == $password['service_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($service['name']) ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <?= Component::createInputText('username', '', $password['username'], 'Username/Email', true) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= Component::createInputText('password', '', $password['password'], 'Password', true, 'password') ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= Component::createInputText('notes', '', $password['notes'] ?? '', 'Note') ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="input-group">
                                    <input type="password" class="form-control password-field" value="<?= htmlspecialchars($password['password']) ?>" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary copy-password" type="button" data-password="<?= htmlspecialchars($password['password']) ?>">
                                            <i class="fa-solid fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <?php if (!empty($password['notes'])): ?>
                                <small class="text-muted"><?= htmlspecialchars($password['notes']) ?></small>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="actions-column">
                        <div class="timestamp">
                            <?= $password['created_at'] ?>
                        </div>
                        <div class="actions">
                            <?php if (!empty($_GET['editpassword']) && ($password['id'] == $_GET['editpassword'])): ?>
                                <a href="#" onclick="document.getElementById('editpassword').submit();">
                                    <i class="fa-solid fa-check"></i>
                                </a>&nbsp;
                                <a href="<?= refreshPage() ?>">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= refreshPage() ?>&editpassword=<?= $password['id'] ?>">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>&nbsp;
                                <a href="<?= refreshPage() ?>&deletepassword=<?= $password['id'] ?>">
                                    <i class="fa-solid fa-trash-can"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($_GET['editpassword']) && ($password['id'] == $_GET['editpassword'])) {
                        echo '<input type="hidden" name="id" value="' . $password['id'] . '">';
                        echo '</form>';
                    } ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<div class="row">
    <div class="col-md-12 p-3">
        <div class="chat-box">
            <div class="message-column">
                <div class="message-text">
                    <em>Nessuna password salvata. Inizia aggiungendo un servizio e le relative password.</em>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Toggle password visibility
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const input = this.parentElement.previousElementSibling;
        const icon = this.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

// Copy password to clipboard
document.querySelectorAll('.copy-password').forEach(button => {
    button.addEventListener('click', function() {
        const password = this.getAttribute('data-password');
        
        // Metodo moderno con navigator.clipboard
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(password).then(() => {
                showCopySuccess(this);
            }).catch(() => {
                // Fallback se navigator.clipboard fallisce
                fallbackCopyTextToClipboard(password, this);
            });
        } else {
            // Fallback per browser più vecchi
            fallbackCopyTextToClipboard(password, this);
        }
    });
});

// Funzione per mostrare il successo della copia
function showCopySuccess(button) {
    const originalIcon = button.querySelector('i');
    originalIcon.classList.remove('fa-copy');
    originalIcon.classList.add('fa-check');
    
    setTimeout(() => {
        originalIcon.classList.remove('fa-check');
        originalIcon.classList.add('fa-copy');
    }, 2000);
}

// Fallback per browser che non supportano navigator.clipboard
function fallbackCopyTextToClipboard(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(button);
        } else {
            // Se anche execCommand fallisce, mostra la password in un alert
            alert('Password: ' + text);
        }
    } catch (err) {
        // Se tutto fallisce, mostra la password in un alert
        alert('Password: ' + text);
    }
    
    document.body.removeChild(textArea);
}
</script> 