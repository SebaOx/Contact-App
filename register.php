<?php

require "database.php";
//Definimos error y lo usaremos más adelante
$error = null;
// Variable superglobales "$_algo", disponible en cualquier archivo de php 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // chequeamos que no esten vacios los campos
    if (empty($_POST["name"]) || empty($_POST["email"]) || empty($_POST["password"])) {
      $error = "Please fill all the fields.";
      // y que el correo tenga el formato adecuado
    } else if (!str_contains($_POST["email"], "@")){
        $error = "Email format is incorrect.";
        // ahora si tomamos los datos
    } else {
        // tomamos el correo que nos pasaa el usuario para verificar que no exista
        // ningun usuario en la base con ese email
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindParam(":email", $_POST["email"]);
        $statement->execute();
        //si existe mandamos el error
        if ($statement->rowCount() > 0) {
          $error = "This email is taken.";
          
        } else {
          //sino creamos el usuario en la tabla tomando los datos que nos pasan
          $conn
            ->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)")
            ->execute([
                ":name" => $_POST["name"],
                ":email" => $_POST["email"],
                ":password" => password_hash($_POST["password"], PASSWORD_BCRYPT)
            ]);
            //hacemos lo mismo que en el login asi registramos y logeamos directamente al usuario nuevo
            $statement = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
            $statement->bindParam(":email", $_POST["email"]);
            $statement->execute();

            $user = $statement->fetch(PDO::FETCH_ASSOC);

            session_start();
            $_SESSION["user"] = $user;

            header("Location: home.php");
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
        <div class="card-header">Register</div>
        <div class="card-body">
          <?php if ($error): ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="register.php">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>

              <div class="col-md-6">
                <input id="email" type="tel" class="form-control" name="email" required autocomplete="email" autofocus>
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
