<?php
include_once("templates/header.php");
?>

  <div id="main-container" class="container-fluid">
    <div class="col-md-12">
      <div class="row" id="auth-row">
        <div class="col-md-4" id="login-container">
          <h2>Entrar</h2>
          <form action="<?php echo $BASE_URL; ?>auth_process.php" method="POST">
            <input type="hidden" name="type" value="login">
            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Senha:</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
            </div>
            <input type="submit" class="btn card-btn" value="Entrar">
          </form>
        </div>

        <div class="col-md-4" id="register-container">
          <h2>Criar Conta</h2>
          <form action="<?php echo $BASE_URL; ?>auth_process.php" method="POST">
            <input type="hidden" name="type" value="register">
            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu e-mail">
            </div>
            <div class="mb-3">
              <label for="name" class="form-label">Nome:</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome">
            </div>
            <div class="mb-3">
              <label for="lastname" class="form-label">Sobrenome:</label>
              <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Digite seu sobrenome">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Senha:</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
            </div>
            <div class="mb-3">
              <label for="confirmpassword" class="form-label">Confirmação de senha:</label>
              <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirme sua senha">
            </div>
            <input type="submit" class="btn card-btn" value="Registrar">
          </form>
        </div>
      </div>
    </div>
  </div>
  
<?php
include_once("templates/footer.php");
?>