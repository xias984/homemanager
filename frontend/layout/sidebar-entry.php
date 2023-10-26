<?php
global $colors;
global $conn;
?>
<div class="row">
    <nav class="col-md-12 d-md-block sidebar rounded" style="background-color: <?=$colors['sfondo']?>">
        <div class="position-sticky">
            <ul class="nav flex-column">
                <li class="nav-item mb-3">
                
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=dashboard">
                        <h5>Dashboard</h5>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#finance">
                        <h5>Finance Manager</h5>
                    </a>
                    <ul class="nav flex-column ml-3 collapse" id="finance">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Registra importo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Prospetto</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#submenu1">
                        <h5>Password Manager</h5>
                    </a>
                    <ul class="nav flex-column ml-3 collapse" id="submenu1">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sottomenu 1.1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sottomenu 1.2</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#submenu2">
                        <h5>Photo Manager</h5>
                    </a>
                    <ul class="nav flex-column ml-3 collapse" id="submenu2">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sottomenu 2.1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sottomenu 2.2</a>
                        </li>
                    </ul>
                </li>
                <?php if (isAdmin()): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-toggle="collapse" data-target="#admin">
                        <h5>Admin Manager</h5>
                    </a>
                    <ul class="nav flex-column ml-3 collapse" id="admin">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=listusers">Lista utenti</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?page=configuration">Configurazione</a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>