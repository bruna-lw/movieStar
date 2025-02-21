<?php

require_once("globals.php");
require_once("db.php");
require_once("models/User.php");
require_once("models/Message.php");
require_once("dao/userDAO.php");

$message = new Message($BASE_URL);

$userDAO = new UserDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Atualizar usuário
if($type === "update") {

  // Resgata os dados do usuário
  $userData = $userDAO->verifyToken();

  // Receber dados do post
  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $bio = filter_input(INPUT_POST, "bio");

  // Criar um novo objeto de usuário
  $user = new User();

  // Preencher os dados do usuário
  $userData->name = $name;
  $userData->lastname = $lastname;
  $userData->email = $email;
  $userData->bio = $bio;

  // Upload da imagem
  if(isset($_FILES["image"]) && !empty( $_FILES["image"]["tmp_name"])) {

    $image = $_FILES["image"];
    $imageTypes = ["image/jpeg", "image/jpg", "image/png"]; //tipos de imagens permitidas
    $jpgArray = ["image/jpeg", "image/jpg"];

    // Chegagem de tipo de imagem
    if(in_array($image["type"], $imageTypes)) {

      // Chegar se é jpg
      if(in_array($image["type"], $jpgArray)) {

        
        $imageFile = imagecreatefromjpeg($image["tmp_name"]);

      // Imagem é png
      } else {

        $imageFile = imagecreatefrompng($image["tmp_name"]);
      }

      $imageName = $user->imageGenerateName();
      
      imagejpeg($imageFile, "./img/users/" . $imageName, 100);

      $userData->image = $imageName;

    } else {

      // Enviar uma msg de erro, de imagem inválida
      $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
    }

  }

  $userDAO->update($userData);

  // Atualizar senha do usuário
} else if ($type === "changepassword") {

  //Receber os dados do POST
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  //Resgata os dados do usuário
  $userData = $userDAO->verifyToken();
  $id = $userData->id;

  if($password === $confirmpassword) {

    $user = new User();

    $finalPassword = $user->generatePassword($password);

    $user->password = $finalPassword;
    $user->id = $id;

    $userDAO->changePassword($user);

  } else {

    // Enviar uma msg de erro, de senhas não coincidem
    $message->setMessage("As senhas não são iguais", "error", "back");
  }

} else {

  // Enviar uma msg de erro, de informações inválidas (type não válido)
  $message->setMessage("Informações inválidas!", "error", "index.php");

}