<?php
require_once("globals.php");
require_once("db.php");
require_once("models/Message.php");
require_once("dao/userDAO.php");

$message = new Message($BASE_URL);

$flassMessage = $message->getMessage();

if(!empty($flassMessage["msg"])) {
  // Limpar a mensagem
  $message->clearMessage();
}

$userDAO = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(false);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MovieStar</title>
  <link rel="short icon" href="<?php echo $BASE_URL; ?>img/moviestar.ico">
  <!-- Boostrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/css/bootstrap.css" integrity="sha512-VcyUgkobcyhqQl74HS1TcTMnLEfdfX6BbjhH8ZBjFU9YTwHwtoRtWSGzhpDVEJqtMlvLM2z3JIixUOu63PNCYQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- CSS do projeto -->
  <link rel="stylesheet" href="<?php echo $BASE_URL; ?>css/styles.css">
</head>
<body>
  <header>
    <nav id="main-navbar" class="navbar navbar-expand-lg">
      <a href="<?php echo $BASE_URL; ?>" class="navbar-brand">
        <img src="<?php echo $BASE_URL; ?>img/logo.svg" alt="MovieStar" id="logo">
        <span id="moviestar-title">MovieStar</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
      <form action="<?php echo $BASE_URL; ?>search.php" method="GET" id="search-form" class="d-flex my-2 my-lg-0">
      <input type="text" name="q" id="search" class="form-control me-2" type="search" placeholder="Buscar Filmes" aria-label="Search">
        <button class="btn my-2 my-sm-0" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </form>
      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav">
          <?php if($userData): ?>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL; ?>newmovie.php" class="nav-link">
                <i class="far fa-plus-square"></i> Incluir Filme
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL; ?>dashboard.php" class="nav-link">Meus Filmes</a>
            </li>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL; ?>editprofile.php" class="nav-link bold">
                <?php echo $userData->name; ?>
              </a>
            </li>
              <li class="nav-item">
            <a href="<?php echo $BASE_URL; ?>logout.php" class="nav-link">Sair</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a href="<?php echo $BASE_URL; ?>auth.php" class="nav-link">Entrar / Cadastrar</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </nav>
  </header>

  <?php if(!empty($flassMessage["msg"])): ?>
    <div class="msg-container">
      <p class="msg <?php echo $flassMessage["type"]; ?>"><?php echo $flassMessage["msg"]; ?></p>
    </div>
  <?php endif; ?>
  