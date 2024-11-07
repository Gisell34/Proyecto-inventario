<?php
include 'database.php';

// Verifica si hay datos en $_POST
if (isset($_POST['nombre_cliente']) && isset($_POST['apellido_cliente']) && isset($_POST['productos']) && isset($_POST['fecha']) && isset($_POST['total'])) {
    $nombre_cliente = $_POST['nombre_cliente'];
    $apellido_cliente = $_POST['apellido_cliente'];
    $productos = $_POST['productos'];
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];

    // Preparar la consulta
    $stmt = $conn->prepare("INSERT INTO facturas (Nombre_cliente, Apellido_cliente, Productos, Fecha, Total) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre_cliente, $apellido_cliente, $productos, $fecha, $total);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Factura registrada exitosamente";
    } else {
        echo "Error al registrar la factura: " . $stmt->error;
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo "Faltan datos para registrar la factura";
}

$conn->close();
?>