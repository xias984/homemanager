<div class="position-sticky">
  Ciao <a href="#" data-bs-toggle="collapse" data-bs-target="#logout" aria-expanded="false">
    <strong><?= $_SESSION['name'] ?></strong>
  </a>
  <div class="collapse ml-3" id="logout" style="background-color: <?=$sfondo2;?>">
    <ul class="list-unstyled">
      <li><a href="index.php?page=settings">Modifica Password</a></li>
      <li><a href="index.php?page=logout&idmsg=3">Logout</a></li>
    </ul>
  </div>
</div>