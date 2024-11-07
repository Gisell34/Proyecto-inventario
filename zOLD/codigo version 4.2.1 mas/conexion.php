<?php
$servername = "localhost";
$usu = "root";
$pass = "";
$dbname = "inventariolicorera";

$conex = new mysqli($servername, $usu, $pass, $dbname);

if ($conex->connect_error) {
    die("Error en la conexión: " . $conex->connect_error);
}