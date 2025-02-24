<?php
include_once("templates/header.php");

require_once("models/Movie.php");
require_once("dao/movieDAO.php");
require_once("dao/reviewDAO.php");

// Pega o id do filme
$id = filter_input(INPUT_GET,"id");

$movie;

$movieDAO = new MovieDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

if(empty($id)) {

  // Enviar uma msg de erro
  $message->setMessage("O filme não foi encontrado!", "error", "index.php");

} else {

  $movie = $movieDAO->findById($id);

  // Verifica se o filme existe
  if(!$movie) {

    // Enviar uma msg de erro, de filme nao encontrado
    $message->setMessage("O filme não foi encontrado!", "error", "index.php");
  }

}

// Chegar se o filme tem imagem
if(empty($movie->image)) {
  $movie->image = "movie_cover.jpg"; //coloca a imagem padrao
}

// Chegar se o filme é do usuário, porque o usuário não pode comentar no filme que cadastrou
$userOwnsMovie = false;

if(!empty($userData)) {

  if($userData->id === $movie->users_id) {

    $userOwnsMovie = true;
  }

  // Verificar se o usuário da fez a review
  $alreadyReviwed = $reviewDAO->hasAlreadyReviewed($id, $userData->id);

}

// Resgatar as reviews do filme
$movieReviews = $reviewDAO->getMoviesReview($id);

?>

<div id="main-container" class="container-fluid">
  <div class="row">
    <div class="offset-md-1 col-md-6 movie-container">
      <h1 class="page-title"><?php echo $movie->title; ?></h1>
      <p class="movie-details">
        <span>Duração: <?php echo $movie->length; ?></span>
        <span class="pipe"></span>
        <span><?php echo $movie->category; ?></span>
        <span class="pipe"></span>
        <span><i class="fas fa-star"></i><?php echo $movie->rating; ?></span>
      </p>
      <iframe src="<?php echo $movie->trailer; ?>" width="560" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
      <p><?php echo $movie->description; ?></p>
    </div>
    <div class="col-md-4">
      <div class="movie-image-container" style="background-image: url('<?php echo $BASE_URL ?>img/movies/<?php echo $movie->image; ?>')"></div>
    </div>
    <div class="offset-md-1 col-md-10" id="reviews-container">
      <h3 id="reviews-title">Avaliações</h3>
      <!-- Verifica se habilita a review para o usuário ou não. Não vai aparecer se ele nao estiver logado, for o dono do filme e se já estiver logado -->
       <?php if(!empty($userData) && !$userOwnsMovie && !$alreadyReviwed): ?>
      <div class="col-md-12" id="review-form-container">
        <h4>Envie sua avaliação:</h4>
        <p class="page-description">Preencha o formulário com a nota e comentário sobre o filme.</p>
        <form action="<?php echo $BASE_URL ?>review_process.php" id="review-form" method="POST">
          <input type="hidden" name="type" value="create">
          <input type="hidden" name="movies_id" value="<?php echo $movie->id; ?>">
          <div class="mb-3">
            <label for="rating" class="form-label">Nota do filme:</label>
            <select name="rating" id="rating" class="form-control">
              <option value="">Selecione</option>
              <option value="10">10</option>
              <option value="9">9</option>
              <option value="8">8</option>
              <option value="7">7</option>
              <option value="6">6</option>
              <option value="5">5</option>
              <option value="4">4</option>
              <option value="3">3</option>
              <option value="2">2</option>
              <option value="1">1</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="review" class="form-label">Seu comentário:</label>
            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
          </div>
          <input type="submit" class="btn card-btn" value="Enviar comentário">
        </form>
      </div>
      <?php endif; ?>
      <!-- Comentários -->
      <?php foreach($movieReviews as $review): ?>
        <?php require("templates/user_review.php"); ?>
      <?php endforeach; ?>
      <?php if(count($movieReviews) == 0): ?>
        <p class="empty-list">Não há comentários para este filme ainda.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
  
<?php
include_once("templates/footer.php");
?>