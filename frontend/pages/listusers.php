<?php
require("./auth/controller/AuthController.class.php");
$users = new AuthController($_GET);
$userData = $users->listUserTable();
$users->removeUser();
?>
<div class="title">
    <h3>Lista utenti</h3>
</div>
<div class="row">
    <div class="col-md-12">
        <table class="table">
            <thead>
                <tr>
                <?php foreach ($userData[0] as $header) { ?>
                    <th scope="col"><?=$header?></th>
                <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($userData, 1) as $user) {?>
                <tr>
                    <?php foreach ($user as $key => $value) { 
                        echo '<td>';
                        switch ($key) {
                            case 3:
                                $checked = $value ? 'checked' : '';    
                                echo '<input class="form-check-input" type="checkbox" name="admin" '.$checked.' disabled>';
                                break;
                            case 4:
                                echo '<a href="index.php?page=listusers&editid=' . $value . '">Modifica</a> - ' .
                                    '<a href="index.php?page=listusers&deleteid=' . $value . '">Cancella</a>';
                                break;
                            default:
                                echo $value;
                        }
                        echo '</td>';
                        }
                    ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>