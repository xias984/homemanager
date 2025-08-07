<?php
require("./finance/controller/FinanceController.class.php");

$category = new FinanceController();
$categoryData = $category->listCategoryTable();

if (!empty($_POST['category'])) {
    $category->registerCategory($_POST['category']);
}

if (!empty($_GET['deleteid']) && isset($_GET['deleteid'])) {
    $category->removeCategory($_GET['deleteid']);
} else if (!empty($_GET['editid'])) {
    if (!empty($_POST) && isset($_POST)) {
        $category->editCategory($_GET['editid'], $_POST);
    }
}
?>
<?= Component::createTitle('Categorie spese/entrate') ?>

<form action="" method="post">
    <div class="row">
        <div class="col-md-3">
        </div>
        <div class="col-md-6">
            <?= Component::createInputText('category', '', '', 'Aggiungi categoria', true) ?>
        </div>
        <div class="col-md-3">
            <?= Component::createSubmitButton('Inserisci', 'primary') ?>
        </div>
    </div>
</form>

<div class="row">&nbsp;</div>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8" style="overflow-x: auto;">
        <table class="table responsive" style="text-align:center">
            <thead>
                <tr>
                <?php foreach ($categoryData[0] as $header) { ?>
                    <th scope="col"><?=$header?></th>
                <?php }?>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($categoryData, 1) as $category):
                    if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $category[3]) {
                        echo '<form action="" method="post" id="editcategory">';
                    }
                ?>
                <tr>
                    <?php foreach ($category as $key => $value) {
                        echo '<td>';
                        if ($key == 0) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $category[3]) {
                                echo '<input type="text" value="' . $value . '" name="' . $key . '">';
                            } else {
                                echo '<strong>' . $value . '</strong>';
                            }
                        }else if ($key == 1 || $key == 2) {
                            echo $value;
                        } else if ($key == 3) {
                            if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $category[3]) {
                                echo '<a href="#" onclick="document.getElementById(\'editcategory\').submit();"><i class="fa-solid fa-check"></i></a> ' .
                                    '<a href="'.refreshPage().'"><i class="fa-solid fa-xmark"></i></a>';
                            } else {
                                echo '<a href="index.php?page=financecategory&editid=' . $value . '"><i class="fa-regular fa-pen-to-square"></i></a> 
                                <a href="index.php?page=financecategory&deleteid=' . $value . '"><i class="fa-solid fa-trash-can"></i></a>';
                            }
                        }
                        echo '</td>';
                    }?>
                </tr>
                <?php 
                if (!empty($_GET['editid']) && isset($_GET['editid']) && $_GET['editid'] == $category[3]) {
                    echo '</form>';
                }
                endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="col-md-2"></div>
</div>