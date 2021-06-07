    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <li class="nav-item"><a href="<?= URL ?>accueil" class="nav-link active mt-2" aria-current="page">Accueil</a></li>
        <li class="nav-item dropdown mt-2">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Toutes les catégories
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="<?= URL ?>informatique">Informatique</a></li>
            <li><a class="dropdown-item" href="<?= URL ?>jeuxVideo">Jeux-vidéo</a></li>
            <li><a class="dropdown-item" href="<?= URL ?>electronique">Electronique</a></li>
            <li><a class="dropdown-item" href="<?= URL ?>electronique">Musique</a></li>
          </ul>
        </li>
        <li class="nav-item ms-2"><a href="<?= URL ?>cart" class="nav-link"><img src="<?= URL ?>public\assets\images\basket.png" width="50px" alt="caddie représentant le panier de client">Votre panier</a></li>
        <li class="nav-item"><a href="<?= URL ?>connect" class="nav-link mt-2 ms-5">Se connecter</a></li>
      </ul>
      <form class="d-flex ms-2">
        <input class="form-control me-2" type="search" placeholder="Rechercher" aria-label="Search">
        <button class="btn btn-outline-secondary" type="submit">Rechercher</button>
      </form>
    </div>
  </div>
</nav>
