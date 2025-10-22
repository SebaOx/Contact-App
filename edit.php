<?php

require "database.php";

$id = $_GET["id"];

//Para evitar que intenten editar un contacto con id inexistente desde consola o modificando la url
$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
$statement->execute([":id" => $id]);

if($statement->rowCount() == 0){
    //si no existe pasamos el mensaje de error 404
    http_response_code(404);
    echo("HTTP 404 NOT FOUND");
    return;
}
//con fetch obtenemos el contacto a editar
$contact = $statement->fetch(PDO::FETCH_ASSOC);

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

      $statement = $conn->prepare("UPDATE contacts SET name = :name, phone_number = :phone_number WHERE id = :id");
      $statement->execute([
        ":id" => $id,
        ":name" => $_POST["name"],
        ":phone_number" => $_POST["phone_number"],
      ]);

      header("Location: index.php");
    }
    
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
  <!-- Bootstrap -->
  <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.3/darkly/bootstrap.min.css"
    integrity="sha512-HDszXqSUU0om4Yj5dZOUNmtwXGWDa5ppESlX98yzbBS+z+3HQ8a/7kcdI1dv+jKq+1V5b01eYurE7+yFjw6Rdg=="
    crossorigin="anonymous" 
    referrerpolicy="no-referrer"
  />
  <script
    defer
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous">
  </script>

  <!-- Static Content -->
  <link rel="stylesheet" href="./static/css/index.css"/>

  <title>Contacts App</title>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand font-weight-bold" href="#">
        <img class="mr-2" src="./static/img/logo.png" />
        ContactsApp
      </a>
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav"
        aria-controls="navbarNav"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="./index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./add.php">Add Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <main>
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
              <!-- aca usamos el metodo POST para subir los cambios pero lo ideal es PUT, pero para eso necesitamos JS -->
              <form method="POST" action="edit.php?id=<?= $contact["id"]?>">
                <div class="mb-3 row">
                  <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
    
                  <div class="col-md-6">
                    <input value="<?= $contact["name"] ?>" id="name" type="text" class="form-control" name="name" required autocomplete="name" autofocus>
                  </div>
                </div>
    
                <div class="mb-3 row">
                  <label for="phone_number" class="col-md-4 col-form-label text-md-end">Phone Number</label>
    
                  <div class="col-md-6">
                    <input value="<?= $contact["phone_number"] ?>" id="phone_number" type="tel" class="form-control" name="phone_number" required autocomplete="phone_number" autofocus>
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
  </main>

</body>
</html>
