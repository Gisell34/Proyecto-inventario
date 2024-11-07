<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoServicio = $_POST['codigo'];

    if (!isset($_SESSION['servicios'])) {
        $_SESSION['servicios'] = array();
    }

    if (array_key_exists($codigoServicio, $_SESSION['servicios'])) {
        $_SESSION['servicios'][$codigoServicio] += 1;
    } else {
        $_SESSION['servicios'][$codigoServicio] = 1;
    }

    header("Location: ver_servicios.php");
    exit();
}
?>