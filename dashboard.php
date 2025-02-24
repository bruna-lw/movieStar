<?php
include_once("templates/header.php");

// Verifica se o usuário está autenticado
require_once("models/User.php");
require_once("dao/userDAO.php");

$user = new User();
$userDAO = new UserDAO($conn, $BASE_URL);

require_once("dao/movieDAO.php");
$movieDAO = new MovieDAO($conn, $BASE_URL);

$userData = $userDAO->verifyToken(true);

$userMovies = $movieDAO->getMovieByUserId($userData->id);

?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title">Dashboard</h2>
  <p class="section-description">Adicione ou atualize as informações dos filmes que vodê enviou.</p>
  <div class="col-md-12" id="add-movie-container">
    <a href="<?php echo $BASE_URL; ?>newmovie.php" class="btn card-btn">
      <i class="fas fa-plus"></i> Adicionar Filme
    </a>
  </div>
  <div class="col-md-12" id="movies-dashboard">
    <table class="table table-custom">
      <thead>
        <th scope="col">#</th>
        <th scope="col">Título</th>
        <th scope="col">Nota</th>
        <th scope="col" class="actions-column">Ações</th>
      </thead>
      <tbody>
        <?php foreach($userMovies as $movie): ?>
        <tr>
          <td scope="row"><?php echo $movie->id; ?></td>
          <td><a href="<?php echo $BASE_URL; ?>movie.php?id=<?php echo $movie->id; ?>" class="table-movie-title"><?php echo $movie->title; ?></a></td>
          <td><i class="fas fa-star"></i><?php echo $movie->rating; ?></td>
          <td class="actions-column">
            <a href="<?php echo $BASE_URL; ?>editmovie.php?id=<?php echo $movie->id; ?>" class="edit-btn">
              <i class="far fa-edit"></i> Editar
            </a>
            <form action="<?php echo $BASE_URL; ?>movie_process.php" method="POST">
              <input type="hidden" name="type" value="delete">
              <input type="hidden" name="id" value="<?php echo $movie->id; ?>">
              <button type="submit" class="delete-btn">
                <i class="fas fa-times"></i> Deletar
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
  
<?php
include_once("templates/footer.php");
?>