<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numeroFactura = $_POST['numero_factura'];
    $fecha = $_POST['fecha'];
    $clienteID = $_POST['cliente_id'];
    $total = $_POST['total'];

    $sql = "INSERT INTO Facturar (NumeroFactura, Fecha, ClienteID, Total) VALUES ('$numeroFactura', '$fecha', $clienteID, $total)";
    if ($conn->query($sql) === TRUE) {
        echo "Factura registrada exitosamente";
    } else {
        echo "Error al registrar la factura: " . $conn->error;
    }

    header("Location: formulario_factura.html");
    exit();
} else {
    header("Location: formulario_factura.html");
    exit();
}
?>