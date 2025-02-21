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

// Verificação do tipo de formulário
if ($type === "register") {

  $name = filter_input(INPUT_POST, "name");
  $lastname = filter_input(INPUT_POST, "lastname");
  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");
  $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

  // Verificação de dados mínimos
  if($name && $lastname && $email && $password) {

    // Verificar se as senhas são iguais
    if($password === $confirmpassword) {

      // Verificar se o email já está cadastrado no sistema
      if($userDAO->findByEmail($email) === false) {

        $user = new User();

        // Criação de token e senha
        $userToken = $user->generateToken();
        $finalPassword = $user->generatePassword($password);

        $user->name = $name;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->password = $finalPassword;
        $user->token = $userToken;

        $auth = true;

        $userDAO->create($user, $auth);

      } else {

        // Enviar uma msg de erro, de email já cadastrado
        $message->setMessage("Usuário já cadastrado, tente outro e-mail", "error", "back");
      }

    } else {

      // Enviar uma msg de erro, de senhas não coincidem
      $message->setMessage("As senhas não são iguais", "error", "back");
    }

  } else {

    // Enviar uma msg de erro, de dados faltantes
    $message->setMessage("Por favor, preencha todos os campos", "error", "back");
  }

} elseif ($type === "login") {

  $email = filter_input(INPUT_POST, "email");
  $password = filter_input(INPUT_POST, "password");

  // Tenta autenticar o usuário
  if($userDAO->authenticateUser($email, $password)) {

    $message->setMessage("Seja bem-vindo!", "success", "editprofile.php");

    // Redireciona o usuário caso não consiga conectar
  } else {

    // Enviar uma msg de erro, de dados incorretos
    $message->setMessage("Usuário e/ou senha incorretos.", "error", "back");

  }

} else {

  // Enviar uma msg de erro, de informações inválidas (type não válido)
  $message->setMessage("Informações inválidas!", "error", "index.php");
}