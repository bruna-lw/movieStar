<?php
include_once("templates/header.php");

// Verifica se o usuário está autenticado
require_once("models/User.php");
require_once("dao/userDAO.php");
require_once("dao/movieDAO.php");

$user = new User();
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);

// Receber o id do usuário
$id = filter_input(INPUT_GET,"id");

if(empty($id)) {

  if(!empty($userData)) {

    $id = $userData->id;
  } else {

    $message->setMessage("Usuário não encontrado!", "error", "index.php");
  }
} else {

  $userData = $userDAO->findById($id);

  // Se nao encontrar usuário
  if(!$userData) {

    $message->setMessage("Usuário não encontrado!", "error", "index.php");
  }
  
}

$fullName = $user->getFullName($userData);

// Coloca a img padrao de usuário caso não tenha uma imagem no BD
if($userData->image == "") {
  $userData->image = "user.png";
}

// Filmes que o usuário adicionou
$userMovies = $movieDAO->getMovieByUserId($id);

?>

<div id="main-container" class="container-fluid">
  <div class="col-md-8 offset-md-2">
    <div class="row profile-container">
      <div class="col-md-12 about-container">
        <h1 class="page-title"><?php echo $fullName; ?></h1>
        <div id="profile-image-container" class="profile-image" style="background-image: url('<?php echo $BASE_URL; ?>img/users/<?php echo $userData->image; ?>')"></div>
        <h3 class="about-title">Sobre:</h3>
        <?php if(!empty($userData->bio)): ?>
          <p class="profile-description"><?php echo $userData->bio; ?></p>
        <?php else: ?>
          <p class="profile-description">O usuário ainda não escreveu nada aqui...</p>
        <?php endif; ?>
      </div>
      <div class="col-md-12 added-movies-container">
        <h3>Filmes que enviou:</h3>
        <div class="movies-container">
          <?php foreach($userMovies as $movie): ?>
            <?php require("templates/movie_card.php"); ?>
          <?php endforeach; ?>
          <?php if(empty($userMovies)): ?>
            <p class="empty-list">O usuário ainda não enviou filmes.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
include_once("templates/footer.php");
?>