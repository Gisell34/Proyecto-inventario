<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['codigo_servicio'])) {
        $codigo_servicio = $_POST['codigo_servicio'];

        if (isset($_SESSION['servicios'][$codigo_servicio])) {
            unset($_SESSION['servicios'][$codigo_servicio]);
            echo "Servicio cancelado correctamente.";
        } else {
            echo "No se pudo cancelar el servicio.";
        }
    }
}