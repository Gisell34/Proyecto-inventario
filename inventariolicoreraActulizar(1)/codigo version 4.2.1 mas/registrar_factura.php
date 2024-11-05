<?php
include 'database.php';

// Verifica si hay datos en $_POST
if (isset($_POST['Nombre_cliente']) && isset($_POST['Apellido_cliente']) && isset($_POST['Productos']) && isset($_POST['Cantidad']) && isset($_POST['Precio']) && isset($_POST['Nombre_servicio']) && isset($_POST['Tipo_servicio']) &&isset($_POST['Precio_venta']) && isset($_POST['Fecha']) && isset($_POST['Total'])) {
    $nombre_cliente = $_POST['Nombre_cliente'];
    $apellido_cliente = $_POST['Apellido_cliente'];
    $productos = $_POST['productos'];
    $cantidad = $_POST['Cantidad'];
    $Precio = $_POST['Precio'];
    $Nombre_servicio = $_POST['Nombre_servicio'];
    $Tipo_servicio = $_POST['Tipo_servicio'];
    $Precio_venta = $_POST['Precio_venta'];
    $fecha = $_POST['Fecha'];
    $total = $_POST['Total'];

    // Preparar la consulta
    $stmt = $conn->prepare("INSERT INTO facturas (Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Nombre_servicio, Tipo_servicio, Precio_venta, Fecha, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssidssdsd", $nombre_cliente, $apellido_cliente, $productos, $cantidad, $Precio, $Nombre_servicio, $Tipo_servicio, $Precio_venta, $fecha, $total);

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