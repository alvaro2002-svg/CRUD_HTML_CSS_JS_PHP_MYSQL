<?php
## Conexión BD.
$db_name = "mysql:host=localhost;dbname=reviews_db";
$db_user_name = "root";
$db_user_pass = "";

## Función datos enviados.
$conn = new PDO($db_name, $db_user_name, $db_user_pass);

## Crear ID aleatorio.
function create_unique_id()
{
    $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $characters_lenght = strlen($characters);
    $random_string = "";

    for ($i = 0; $i < 20; $i++) {
        $random_string .= $characters[mt_rand(0, $characters_lenght - 1)];
    }
    return $random_string;
}

## Crear COOKIE.
if (isset($_COOKIE["user_id"])) {
    $user_id = $_COOKIE["user_id"];
} else {
    $user_id = "";
}
