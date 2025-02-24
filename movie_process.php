<?php

require_once("globals.php");
require_once("db.php");
require_once("models/Movie.php");
require_once("models/Message.php");
require_once("dao/userDAO.php");
require_once("dao/movieDAO.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);
$movieDAO = new MovieDAO($conn, $BASE_URL);


// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Resgata os dados do usuário na variável userData
$userData = $userDAO->verifyToken();

if ($type == "create") {

  // Receber os dados dos inputs
  $title = filter_input(INPUT_POST,"title");
  $length = filter_input(INPUT_POST,"length");
  $category = filter_input(INPUT_POST,"category");
  $trailer = filter_input(INPUT_POST,"trailer");
  $description = filter_input(INPUT_POST,"description");

  $movie = new Movie();

  // Validação mínima de dados
  if(!empty($title) && !empty($category) && !empty($description)) {

    $movie->title = $title;
    $movie->length = $length;
    $movie->category = $category;
    $movie->trailer = $trailer;
    $movie->description = $description;
    $movie->users_id = $userData->id;


    // Upload de imagem do filme
    if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

      $image = $_FILES["image"];
      $imageTypes = ["image/jpeg", "image/jpg", "image/png"]; //tipos de imagens permitidas
      $jpgArray = ["image/jpeg", "image/jpg"];
      
      

      if(in_array($image["type"], $imageTypes)) {

        // Checar se é jpg
        if(in_array($image["type"], $jpgArray)) {

          $imageFile = imagecreatefromjpeg($image["tmp_name"]);

        // É png
        } else {

        $imageFile = imagecreatefrompng($image["tmp_name"]);

        print_r($imageFile); exit;

      }

      // Gerar o nome da imagem
      $imageName = $movie->imageGenerateName();

      // Salvar a imagem no servidor
      imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

      $movie->image = $imageName;

      } else {

        // Enviar uma msg de erro, de imagem inválida
        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");

      }
      
    }  
    
    $movieDAO->create($movie);

  } else {

    // Enviar uma msg de erro, de falta de informações mínimas
    $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria.", "error", "back");

    }

} else if ($type === "delete") {

  // Receber o id do input
  $id = filter_input(INPUT_POST,"id");

  $movie = $movieDAO->findById($id);

  if($movie) {

    // Verificar se o filme é do usuário
    if($movie->users_id === $userData->id) {

      $movieDAO->destroy($movie->id);
    }

  } else {

    // Enviar uma msg de erro
    $message->setMessage("Filme não encontrado!", "error", "index.php");
  }

} else if ($type === "update") {

  // Receber os dados dos inputs
  $title = filter_input(INPUT_POST,"title");
  $length = filter_input(INPUT_POST,"length");
  $category = filter_input(INPUT_POST,"category");
  $trailer = filter_input(INPUT_POST,"trailer");
  $description = filter_input(INPUT_POST,"description");
  $id = filter_input(INPUT_POST,"id");

  $movieData = $movieDAO->findById($id);

  if($movieData) {

    // Verificar se o filme é do usuário
    if($movieData->users_id === $userData->id) {

      if(!empty($title) && !empty($category) && !empty($description)) {

        $movieData->title = $title;
        $movieData->length = $length;
        $movieData->category = $category;
        $movieData->trailer = $trailer;
        $movieData->description = $description;

        // Upload de imagem do filme
        if(isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

          $image = $_FILES["image"];
          $imageTypes = ["image/jpeg", "image/jpg", "image/png"]; //tipos de imagens permitidas
          $jpgArray = ["image/jpeg", "image/jpg"];

          if(in_array($image["type"], $imageTypes)) {

            // Checar se é jpg
            if(in_array($image["type"], $jpgArray)) {

              $imageFile = imagecreatefromjpeg($image["tmp_name"]);

            // É png
            } else {

            $imageFile = imagecreatefrompng($image["tmp_name"]);

            }

            // Gerar o nome da imagem
            $imageName = $movieData->imageGenerateName();

            // Salvar a imagem no servidor
            imagejpeg($imageFile, "./img/movies/" . $imageName, 100);

            $movieData->image = $imageName;

          } else {

          // Enviar uma msg de erro, de imagem inválida
          $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
          }
      
        }  

        $movieDAO->update($movieData);
        
      } else {

        // Enviar uma msg de erro, de falta de informações mínimas
        $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria.", "error", "back");
      }

      
    }

  } else {

    // Enviar uma msg de erro
    $message->setMessage("Filme não encontrado!", "error", "index.php");
  }

} else {

  // Enviar uma msg de erro, de informações inválidas (type não válido)
  $message->setMessage("Informações inválidas!", "error", "index.php");

}
