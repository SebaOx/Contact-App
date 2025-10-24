<?php

require "database.php";

$id = $_GET["id"];

//Para evitar que intenten borrar un contacto con id inexistente desde consola o modificando la url
$statement = $conn->prepare("SELECT * FROM contacts WHERE id = :id");
$statement->execute([":id" => $id]);

if($statement->rowCount() == 0){
    //si no existe pasamos el mensaje de error 404
    http_response_code(404);
    echo("HTTP 404 NOT FOUND");
    return;
}

//ahora si logica general para borrado dedse la pag web
$conn->prepare("DELETE FROM contacts WHERE id = :id")->execute([":id" => $id]);


header("Location: home.php");
