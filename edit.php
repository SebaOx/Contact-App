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
<!-- import del footer -->
<?php require "parciales/footer.php" ?>
