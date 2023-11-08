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
<div class="title">
    <h3>Dashboard</h3>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="chat-box p-3">
            <form class="custom-form" action="" method="post">
                <div class="message-column">
                    <div class="message">
                        <input type="text" class="form-control" name="newpost" placeholder="Scrivi nuovo post">
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Invia</button>
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
                    echo '<input type="text" class="form-control" name="editpost" value="'.$post['post'].'">';
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
                        echo '<a href="#" data-toggle="collapse" data-target="#reply'.$post['idreply'].'">Rispondi</a>';
                    }?>
                    <?php if (($post['iduser'] === $_SESSION['iduser']) || isAdmin()): ?>
                    <?php if (!empty($_GET['editpost']) && ($post['id'] == $_GET['editpost'])) { 
                        echo '<a href="#" onclick="document.getElementById(\'editpost\').submit();">Conferma</a><br>
                            <a href="'.refreshPage().'">Chiudi</a>';
                     } else { ?>
                    <br><a href="index.php?page=dashboard&editpost=<?=$post['id']?>">Modifica</a>
                    <br><a href="index.php?page=dashboard&deletepost=<?=$post['idreply']?>">Cancella</a>
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
                        <input type="text" class="form-control" name="newreply" placeholder="Scrivi la tua risposta">
                        <input type="hidden" name="idreply" value="<?= $post['idreply']?>">
                    </div>
                </div>
                <div class="actions-column">
                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Invia</button>
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
                    if (!empty($_GET['editpost']) && ($reply['id'] == $_GET['editpost'])){?>
                    <input type="text" class="form-control" name="editpost" value="<?=$reply['post']?>">
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
                        echo '<a href="#" onclick="document.getElementById(\'editpost\').submit();">Conferma</a><br>
                            <a href="'.refreshPage().'">Chiudi</a>';
                    } else {?>
                    <a href="index.php?page=dashboard&editpost=<?=$reply['id']?>">Modifica</a><br>
                    <a href="index.php?page=dashboard&deletereply=<?=$reply['id']?>">Cancella</a>
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