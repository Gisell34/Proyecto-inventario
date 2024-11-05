<?php
include('conexion.php');

if (!isset($_GET['id_compra'])) {
    die("ID de compra no proporcionado.");
}

$id_compra = $_GET['id_compra'];

// Obtener información de la compra
$sql_compra = "SELECT c.id_compra, c.fecha_compra, cl.Nombres, cl.Apellidos, cl.Direccion, cl.Correo, c.total
               FROM compras c
               JOIN cliente cl ON c.id_usuario = cl.CodigoCliente
               WHERE c.id_compra = ?";
$stmt_compra = $conex->prepare($sql_compra);

if ($stmt_compra === false) {
    die("Error al preparar la consulta de compra: " . $conex->error);
}

$stmt_compra->bind_param("i", $id_compra);
$stmt_compra->execute();
$resultado_compra = $stmt_compra->get_result();

if ($resultado_compra->num_rows == 0) {
    die("Compra no encontrada.");
}

$compra = $resultado_compra->fetch_assoc();

// Obtener detalles de la compra
$sql_detalles = "SELECT dc.codigoproducto, dc.cantidad, dc.precio, p.Nombre_producto
                 FROM detalles_compras dc
                 JOIN producto p ON dc.codigoproducto = p.Codigoproducto
                 WHERE dc.id_compra = ?";
$stmt_detalles = $conex->prepare($sql_detalles);

if ($stmt_detalles === false) {
    die("Error al preparar la consulta de detalles de compra: " . $conex->error);
}

$stmt_detalles->bind_param("i", $id_compra);
$stmt_detalles->execute();
$resultado_detalles = $stmt_detalles->get_result();

// Generar la factura (aquí simplemente se muestra, pero se puede convertir a PDF u otro formato)
echo "<h1>Factura</h1>";
echo "<p><strong>Compra ID:</strong> " . htmlspecialchars($compra['id_compra']) . "</p>";
echo "<p><strong>Fecha:</strong> " . htmlspecialchars($compra['fecha_compra']) . "</p>";
echo "<p><strong>Cliente:</strong> " . htmlspecialchars($compra['Nombres'] . " " . $compra['Apellidos']) . "</p>";
echo "<p><strong>Dirección:</strong> " . htmlspecialchars($compra['Direccion']) . "</p>";
echo "<p><strong>Correo:</strong> " . htmlspecialchars($compra['Correo']) . "</p>";
echo "<p><strong>Total:</strong> " . htmlspecialchars($compra['total']) . "</p>";

echo "<h2>Productos</h2>";
echo "<table border='1'>";
echo "<tr><th>Producto</th><th>Cantidad</th><th>Precio</th></tr>";
while ($detalle = $resultado_detalles->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($detalle['Nombre_producto']) . "</td>";
    echo "<td>" . htmlspecialchars($detalle['cantidad']) . "</td>";
    echo "<td>" . htmlspecialchars($detalle['precio']) . "</td>";
    echo "</tr>";
}
echo "</table>";

// Liberar recursos
$stmt_compra->close();
$stmt_detalles->close();
$conex->close();
?>