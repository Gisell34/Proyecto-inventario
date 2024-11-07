<?php
$servername = "localhost";
$usu = "root"; // Ajustar según la configuración
$pass = ""; // Ajustar según la configuración
$dbname = "inventariolicorera";

$conex = new mysqli($servername, $usu, $pass, $dbname);

if ($conex->connect_error) {
    die("Error en la conexión: " . $conex->connect_error);
}
?>