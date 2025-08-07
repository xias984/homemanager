<?php
require("./social/controller/SocialController.class.php");

$newPost = new SocialController();

if (!empty($_POST['newpost']) && isset($_POST['newpost'])) {
    $newPost->insertPost($_POST['newpost']);    
}

$posts = $newPost->selectPosts();
if (!empty($_POST['newreply']) && isset($_POST['newreply'])) {
    $newPost->insertPost($_POST['newreply'], $_POST['idreply']);
}

if (!empty($_GET['deletepost']) || !empty($_GET['deletereply'])) {
    $newPost->deletePost($_GET);
}

if (!empty($_GET['editpost']) && isset($_GET['editpost'])) {
    $newPost->editPost($_GET,$_POST);
}

?>
<?= Component::createTitle('Dashboard') ?>
<div class="row">
    <div class="col-md-12">
        <div class="chat-box p-3">
            <form class="custom-form" action="" method="post">
                <div class="message-column">
                    <div class="message">
                        <?= Component::createInputText('newpost', '', '', 'Scrivi nuovo post', true) ?>
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <?= Component::createSubmitButton('<i class="fa-solid fa-paper-plane"></i>', 'primary') ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php foreach ($posts as $post) { ?>
<div class="row">
    <div class="col-md-12 p-3">
        <div class="chat-box" class="rounded">
            <?php if (!empty($_GET['editpost']) && ($post['id'] == $_GET['editpost'])) {
                echo '<form class="custom-form" action="" method="post" id="editpost">';
            } ?>
            <div class="message-column">
                <div class="message">
                    <?=$post['username']?>:
                </div>
                <div class="message-text">
                <?php if (!empty($_GET['editpost']) && ($post['id'] == $_GET['editpost'])) {
                    Component::createInputText('editpost', '', $post['post'], '');
                } else {
                    echo $post['post'];
                } ?>
                </div>
            </div>
            <div class="actions-column">
                <div class="timestamp">
                    <?=$post['data']?>
                </div>
                <div class="actions">
                    <?php if (!empty($_GET['editpost']) && ($post['id'] == $_GET['editpost'])) {
                        echo '';
                    } else {
                        echo '<a href="#" data-toggle="collapse" data-target="#reply'.$post['idreply'].'"><i class="fa-solid fa-reply"></i></a>&nbsp;';
                    }?>
                    <?php if (($post['iduser'] === $_SESSION['iduser']) || isAdmin()): ?>
                    <?php if (!empty($_GET['editpost']) && ($post['id'] == $_GET['editpost'])) { 
                        echo '<a href="#" onclick="document.getElementById(\'editpost\').submit();"><i class="fa-solid fa-check"></i></a> 
                            <a href="'.refreshPage().'"><i class="fa-solid fa-xmark"></i></a>';
                     } else { ?>
                    <a href="<?=refreshPage()?>&editpost=<?=$post['id']?>"><i class="fa-regular fa-pen-to-square"></i></a>&nbsp;
                    <a href="<?=refreshPage()?>&deletepost=<?=$post['idreply']?>"><i class="fa-solid fa-trash-can"></i></a>
                    <?php } endif; ?>
                </div>
            </div>
            <?php if (!empty($_GET['editpost']) && ($post['id'] == $_GET['editpost'])) {
                echo '</form>';
            } ?>
        </div>
        <div class="chat-box collapse" id="reply<?=$post['idreply']?>">
            <form class="custom-form" action="" method="post">
                <div class="message-column">
                    <div class="message-text">
                        <?= Component::createInputText('newreply', '', '', 'Scrivi una risposta') ?>
                        <?= Component::createInputText('idreply', '', $post['idreply'], '', false, 'hidden'); ?>
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <?= Component::createSubmitButton('Invia', 'primary') ?>
                    </div>
                </div>
            </form>
        </div>

        <?php foreach ($newPost->selectReplies($post['idreply']) as $reply) { ?>
        <div class="chat-box" class="rounded">
            <?php if (!empty($_GET['editpost']) && ($reply['id'] == $_GET['editpost'])) {
                echo '<form class="custom-form" action="" method="post" id="editpost">';
            } ?>
            <div class="message-column">
                <div class="answer">
                    <?=$reply['username']?>:
                </div>
                <div class="answer-text">
                <?php 
                    if (!empty($_GET['editpost']) && ($reply['id'] == $_GET['editpost'])){
                        Component::createInputText('editpost', '', $reply['post'], '') ?>
                    <?php } else { 
                        echo $reply['post'];
                    } ?>
                </div>
            </div>
            <div class="actions-column">
                <div class="timestamp">
                    <?=$reply['data']?>
                </div>
                <div class="actions">
                <?php if (($reply['iduser'] === $_SESSION['iduser']) || isAdmin()):?>
                    <?php if (!empty($_GET['editpost']) && ($reply['id'] == $_GET['editpost'])) {
                        echo '<a href="#" onclick="document.getElementById(\'editpost\').submit();"><i class="fa-solid fa-check"></i></a>&nbsp;
                            <a href="'.refreshPage().'"><i class="fa-solid fa-xmark"></i></a>';
                    } else {?>
                    <a href="<?=refreshPage()?>&editpost=<?=$reply['id']?>"><i class="fa-regular fa-pen-to-square"></i></a>&nbsp;
                    <a href="<?=refreshPage()?>&deletereply=<?=$reply['id']?>"><i class="fa-solid fa-trash-can"></i></a>
                <?php } endif; ?>
                </div>
            </div>
            <?php if (!empty($_GET['editpost']) && ($reply['id'] == $_GET['editpost'])) {
                echo '</form>';
            } ?>
        </div>
        <?php } ?>

    </div>
</div>
<?php } ?>