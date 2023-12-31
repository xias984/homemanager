<?php
    global $messages;
	global $title;
	global $description;
	global $logo;

    $theme = new ConfigurationController();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    body {
        background-color: <?=$colors['sfondo']?>;
        color: <?=$colors['principale']?>;
    }

    .container {
        background-color: <?=$colors['sfondo2']?>;
    }

    h1,
    h2 {
        color: <?=$colors['secondario']?>;
    }

    h3,
    h4,
    h5,
    h6 {
        color: <?=$colors['subtitle']?>;
    }

    a {
        color: <?=$colors['link']?>;
        text-decoration: none;
    }

    .fixed-top-alert {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 999;
        opacity: 0.7;
    }

    .file-input {
        max-width: 100%;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    /* Social */
    .chat-box {
        width: 100%;
        background-color: <?=$colors['sfondo']?>;
        padding: 10px;
        display: flex;
        border: 1px solid <?=$colors['sfondo2']?>;
    }

    .message-column {
        flex: 85%;
        padding-right: 10px;
    }

    .message {
        font-weight: bold;
        color: <?= $colors['link']?>;
    }

    .answer {
        font-weight: bold;
        color: <?= $colors['link']?>;
        text-align:right;
    }

    .answer-text {
        text-align:right;   
    }

    .actions-column {
        flex: 15%;
        padding-left: 10px;
        text-align: right;
    }

    .timestamp {
        font-size: 12px;
        color: <?=$colors['secondario']?>;
        margin-bottom: 10px;
    }

    .actions {
        font-size: 12px;
    }

    .title {
        text-align:center;
        margin-bottom: 20px;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* Form dashboard */
    .custom-form {
        display: flex;
        width: 100%;
    }

    /* Tabelle */
    .table {
        background-color: <?=$colors['sfondo2']?>;
        color: <?=$colors['principale']?>;
    }

    .table th {
        background-color: <?=$colors['sfondo']?>;
        color: <?=$colors['secondario']?>;
    }
    </style>
</head>

<body>
    <div class="container p-5">
        <div class="row">
            <div class="col-md-2" style="text-align:center">
                <?php if (!empty($logo)) { ?>
                <img src="<?= $logo?>" alt="<?= $title?>" width="100" height="100">
                <?php }?>
            </div>
            <div class="col-md-7">
                <h1 class="p-3"><?php echo '<a href="index.php">'.strtoUpper($title) .'</a>'; ?></h1>
                <h3><?= strtoUpper($description) ?></h3>
            </div>
            <div class="col-md-3">
                <?php getMenu(); ?>
            </div>
        </div>
        <hr>
        <div class="row fixed-top-alert">
            <div class="col-md-4"></div>
            <div class="col-md-4">
                <?php 
                if (isset($_GET['idmsg']) && !empty($_GET['idmsg'])){
                    foreach ($messages as $message) {
                        if ($message['id'] == $_GET['idmsg']) {
                            ?>
                <div id="alert" class="alert alert-<?=$message['style']?>" role="alert" style="text-align:center">
                    <strong><?=$message['message']?></strong>
                </div>
                <?php
                        }
                    }
                }
                ?>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-9 p-2">
                <?php getPage();?>
            </div>
            <div class="col-md-3 p-2">
                <?php 
                getSidebar(); 
                ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"></script>
    <script src="assets/scripts.js"></script>
</body>

</html>