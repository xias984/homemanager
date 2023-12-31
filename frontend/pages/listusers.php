<?php
require("./auth/controller/AuthController.class.php");
$users = new AuthController($_GET);
$userData = $users->listUserTable();

if (!empty($_GET['deleteid']) && isset($_GET['deleteid'])) {
    $users->removeUser();
} else if (!empty($_GET['editid']) && isset($_GET['editid'])) {
    if (!empty($_POST) && isset($_POST)) {
        $users->editUser($_POST);
    }
}

?>
<div class="title">
    <h3>Lista utenti</h3>
</div>
<div class="row">
    <div class="col-md-12" style="overflow-x: auto;">
        <table class="table responsive">
            <thead>
                <tr>
                <?php foreach ($userData[0] as $header) { ?>
                    <th scope="col"><?=$header?></th>
                <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($userData, 1) as $user) {
                if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                    echo '<form action="" method="post" id="edituser">';
                }    
                ?>
                <tr>
                    <?php foreach ($user as $key => $value) {
                        $name = $key."-".$user[4];
                        //apro la cella
                        echo '<td>';
                        //attribuisco il valore per ogni cella
                        if ($key === 0 || $key === 1 || $key === 2) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                                echo '<input type="text" value="' . $value . '" name="' . $key . '">';
                            } else {
                                echo $value;
                            }
                        } elseif ($key === 3) {
                            $checked = $value ? 'checked' : '';
                            $disabled = !empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4] ?: 'disabled';
                            echo '<input class="form-check-input" type="checkbox" name="' . $key . '" ' . $checked . ' ' . $disabled . '>';
                        } elseif ($key === 4) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                                echo '<a href="#" onclick="document.getElementById(\'edituser\').submit();">Conferma</a> - ' .
                                    '<a href="'.refreshPage().'">Chiudi</a>';
                            } else {
                                echo '<a href="index.php?page=listusers&editid=' . $value . '">Modifica</a> - ' .
                                    '<a href="index.php?page=listusers&deleteid=' . $value . '">Cancella</a>';
                            }
                        } else {
                            echo $value;
                        }
                        echo '</td>';
                        
                    }
                    ?>
                </tr>
                <?php 
                if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $user[4]) {
                    echo '</form>';
                }
            } ?>
            </tbody>
        </table>
    </div>
</div>