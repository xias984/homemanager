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
            <div class="message-column">
                <div class="message">
                    <?=$post['username']?>:
                </div>
                <div class="message-text">
                    <?=$post['post']?>
                </div>
            </div>
            <div class="actions-column">
                <div class="timestamp">
                    <?=$post['data']?>
                </div>
                <div class="actions">
                    <a href="#" data-toggle="collapse" data-target="#reply<?=$post['idreply']?>">Rispondi</a>
                    <br> Modifica
                    <br> <a href="index.php?page=dashboard&deletepost=<?=$post['idreply']?>">Cancella</a>
                </div>
            </div>
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
            <div class="message-column">
                <div class="answer">
                    <?=$reply['username']?>:
                </div>
                <div class="answer-text">
                    <?=$reply['post']?>
                </div>
            </div>
            <div class="actions-column">
                <div class="timestamp">
                    <?=$reply['data']?>
                </div>
                <div class="actions">
                <?php if (!empty($reply['iduser']) || isAdmin()) {?>Modifica <br><a href="index.php?page=dashboard&deletereply=<?=$reply['id']?>">Cancella</a><?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

    </div>
</div>
<?php } ?>