<?php
global $colors;
?>
<div class="position-sticky">
    Ciao <a href="#" data-toggle="collapse" data-target="#logout"><strong><?= $_SESSION['name'] ?></strong>&nbsp;<i class="fa-solid fa-angle-down"></i></a> 
    <ul class="flex-column ml-3 collapse" id="logout" style="list-style: none; background-color:<?=$colors['sfondo2']?>">
        <li><a href="index.php?page=settings">Modifica Password</a></li>
        <li><a href="index.php?page=logout&idmsg=3">Logout</a></li>
    </ul>
</div>