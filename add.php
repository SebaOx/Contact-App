<?php

require "database.php";
//Definimos error y lo usaremos más adelante
$error = null;
// Variable superglobales "$_algo", disponible en cualquier archivo de php 
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validaciones de los campos
    if (empty($_POST["name"]) || empty($_POST["phone_number"])) {
      $error = "Por favor rellene todos los campos.";
    }else if (strlen($_POST["phone_number"]) < 9) {
      $error = "EL numero de telefono debe ser de al menos 9 dígitos.";
    }else{
      $name = $_POST["name"];
      $phoneNumber = $_POST["phone_number"];

      //se maneja con sentencias cuando se agrega un contacto para evitar inyecciones SQL
      $statement = $conn->prepare("INSERT INTO contacts (name, phone_number) VALUES (:name, :phone_number)");
      $statement->bindParam(":name", $_POST["name"]);
      $statement->bindParam(":phone_number", $_POST["phone_number"]);
      $statement->execute();

      header("Location: index.php");
    }
    
  }

?>

<!-- import del header -->
<?php require "parciales/header.php" ?>

<div class="container pt-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">Add New Contact</div>
        <div class="card-body">
          <?php if ($error): ?>
            <p class="text-danger">
              <?= $error ?>
            </p>
          <?php endif ?>
          <form method="POST" action="add.php">
            <div class="mb-3 row">
              <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>

              <div class="col-md-6">
                <input id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus>
              </div>
            </div>

            <div class="mb-3 row">
              <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>

              <div class="col-md-6">
                <input id="phone_number" type="tel" class="form-control" name="phone_number" required autocomplete="phone_number" autofocus>
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
