<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoServicio = isset($_POST['codigo']) ? $_POST['codigo'] : null;
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;

    if ($codigoServicio === null) {
        echo "Código de servicio no proporcionado.";
        exit();
    }

    if (!isset($_SESSION['servicios'])) {
        $_SESSION['servicios'] = array();
    }

    if (array_key_exists($codigoServicio, $_SESSION['servicios'])) {
        $_SESSION['servicios'][$codigoServicio] += $cantidad;
    } else {
        $_SESSION['servicios'][$codigoServicio] = $cantidad;
    }

    // Obtener los datos del servicio para completar la información en el carrito
    $sql = "SELECT Nombre_servicio, Precio_venta FROM servicios WHERE Codigo = ?";
    if ($stmt = $conex->prepare($sql)) {
        $stmt->bind_param("i", $codigoServicio);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $_SESSION['servicios'][$codigoServicio]['nombre'] = $row['Nombre_servicio'];
            $_SESSION['servicios'][$codigoServicio]['precio'] = $row['Precio_venta'];
        }
        $stmt->close();
    }

    $_SESSION['mensaje'] = "Servicio agregado al carrito.";
    header("Location: carrito.php");
}
?>