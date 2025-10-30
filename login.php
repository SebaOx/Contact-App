<?php

require "database.php";
//Definimos error y lo usaremos más adelante
$error = null;
// Variable superglobales "$_algo", disponible en cualquier archivo de php 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // chequeamos que no esten vacios los campos
    if (empty($_POST["email"]) || empty($_POST["password"])) {
      $error = "Please fill all the fields.";
      // y que el correo tenga el formato adecuado
    } else if (!str_contains($_POST["email"], "@")){
        $error = "Email format is incorrect.";
        // ahora si tomamos los datos
    } else {
        // tomamos el correo que nos pasaa el usuario para verificar que no exista
        // ningun usuario en la base con ese email
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();
        //si existe mandamos el error
        if ($statement->rowCount() > 0) {
          $error = "Invalid credentials.";
          
        } else {
          //sino tomamos el usuario desde la base de datos
          $user = $statement->fetch(PDO::FETCH_ASSOC);
          //chequeamos que su contraseña sea correcta
          if(!password_verify($_POST["password"],$user["password"])){
            $error = "Invalid credentials.";
          }else{
            // iniciamos la sesion, esto crea la sesion si es la primera vez y sino manda la cookie
            // al servidor para poder cargar una sesion existente.
            session_start();

            //sacamos la contraseña de user para evitar hackeo a la sesion
            unset($user["password"]);

            //usamos la supervariable para cargarle el usuario
            $_SESSION["user"] = $user;

            header("Location: home.php");
          }
        }
    }
  }

?>

<!-- import del header -->
<?php require "parciales/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Login</div>
        <div class="card-body">
          <?php if ($error): ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="login.php">

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" required autocomplete="email" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

              <div class="col-md-6">
                <input id="password" type="password" class="form-control" name="password" required autocomplete="password" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- import del footer -->
<?php require "parciales/footer.php" ?>
