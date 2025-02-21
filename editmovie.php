<?php
include_once("templates/header.php");

// Verifica se o usuário está autenticado
require_once("models/User.php");
require_once("dao/userDAO.php");

$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(true);

require_once("dao/movieDAO.php");

// Pega o id do filme
$id = filter_input(INPUT_GET,"id");

$movieDAO = new MovieDAO($conn, $BASE_URL);

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

?>

<div id="main-container" class="container-fluid">
  <div class="col-md-12">
    <div class="row">
      <div class="col-md-4 offset-md-2">
        <h1><?php echo $movie->title; ?></h1>
        <p class="page-description">Altere os dados do filme no formulário abaixo:</p>
        <form action="<?php echo $BASE_URL; ?>movie_process.php" id="edit-movie-form" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="type" value="update">
          <input type="hidden" name="id" value="<?php echo $movie->id; ?>">
          <div class="mb-3">
            <label for="title" class="form-label">Título:</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Digite o título do filme" value="<?php echo $movie->title; ?>">
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Imagem:</label>
            <input type="file" class="form-control" name="image" id="image">
          </div>
          <div class="mb-3">
            <label for="length" class="form-label">Duração:</label>
            <input type="text" class="form-control" id="length" name="length" placeholder="Digite a duração do filme" value="<?php echo $movie->length; ?>">
          </div>
          <div class="mb-3">
            <label for="category" class="form-label">Categoria:</label>
            <select name="category" id="category" class="form-control">
              <option value="">Selecione</option>
              <option value="Ação" <?php echo $movie->category === "Ação" ? "selected" : ""; ?>>Ação</option>
              <option value="Comédia" <?php echo $movie->category === "Comédia" ? "selected" : ""; ?>>Comédia</option>
              <option value="Drama" <?php echo $movie->category === "Drama" ? "selected" : ""; ?>>Drama</option>
              <option value="Fantasia / Ficção" <?php echo $movie->category === "Fantasia / Ficção" ? "selected" : ""; ?>>Fantasia / Ficção</option>
              <option value="Romance" <?php echo $movie->category === "Romance" ? "selected" : ""; ?>>Romance</option>
              <option value="Suspense" <?php echo $movie->category === "Suspense" ? "selected" : ""; ?>>Suspense</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="trailer" class="form-label">Trailer:</label>
            <input type="text" class="form-control" id="trailer" name="trailer" placeholder="Insira o link do trailer" value="<?php echo $movie->trailer; ?>">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Descrição:</label>
            <textarea name="description" id="description" rows="5" class="form-control"
              placeholder="Descreva o filme..."><?php echo $movie->description; ?></textarea>
          </div>
          <input type="submit" class="btn card-btn" value="Editar filme">
        </form>
      </div>
      <div class="col-md-3">
        <div class="movie-image-container" style="background-image: url('<?php echo $BASE_URL; ?>img/movies/<?php echo $movie->image; ?>')"></div>
      </div>
    </div>
  </div>
</div>

<?php
include_once("templates/footer.php");
?>