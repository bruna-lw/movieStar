<?php
include_once("templates/header.php");

include_once("dao/movieDAO.php");

// DAO dos filmes
$movieDAO = new MovieDAO($conn, $BASE_URL);

// Resgatar a busca do usuário
$q = filter_input(INPUT_GET, "q");

$movies = $movieDAO->findByTitle($q);

?>

<div id="main-container" class="container-fluid">
  <h2 class="section-title">Você está buscando por: <span id="search-result"><?php echo $q; ?></span></h2>
  <p class="section-description">Resultados de busca retornados com base na sua pesquisa:</p>
  <div class="movies-container">
    <?php foreach($movies as $movie):?>
    <?php require("templates/movie_card.php"); ?>
    <?php endforeach;?>
    <?php if(count($movies) === 0): ?>
    <p class="empty-list">Não há filmes para esta busca, <a href="<?php echo $BASE_URL; ?>index.php" class="back-link">voltar</a>.</p>
    <?php endif;?>
  </div>
</div>

<?php
include_once("templates/footer.php");
?>