<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("models/Review.php");
require_once("dao/userDAO.php");
require_once("dao/movieDAO.php");
require_once("dao/reviewDAO.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);
$reviewDAO = new ReviewDAO($conn, $BASE_URL);

// Resgata os dados do usuário na variável userData
$userData = $userDAO->verifyToken();

// Recebendo o tipo do formulário
$type = filter_input(INPUT_POST, "type");

if($type === "create") {

  // Recebendo os dados do post
  $rating = filter_input(INPUT_POST,"rating");
  $review = filter_input(INPUT_POST,"review");
  $movies_id = filter_input(INPUT_POST,"movies_id");

  $reviewObject = new Review();

  $movieData = $movieDAO->findById($movies_id);

  // Verificar se o filme existe
  if($movieData) {

    // Verificar dados mínimos
    if(!empty($rating) && !empty($review) && !empty($movies_id)) {

      $reviewObject->rating = $rating;
      $reviewObject->review = $review;
      $reviewObject->movies_id = $movies_id;
      $reviewObject->users_id = $userData->id;

      $reviewDAO->create($reviewObject);

    } else {

      // Enviar uma msg de erro, de informações mínimas
      $message->setMessage("Você precisa inserir a nota e o comentário!", "error", "back");
    }


  } else {

    // Enviar uma msg de erro, de filme não encontrado
    $message->setMessage("Informações inválidas!", "error", "index.php");
  }

} else {

  // Enviar uma msg de erro, de informações inválidas (type não válido)
  $message->setMessage("Informações inválidas!", "error", "index.php");
}

?>