<?php
// Incluir el archivo de conexión a la base de datos
include 'conexion.php';

// Obtener el ID de la factura desde la URL
$id_factura = isset($_GET['ID']) ? intval($_GET['ID']) : 0;

// Verificar si el ID de la factura es válido
if ($id_factura <= 0) {
    echo "ID de factura no válido.";
    exit();
}

// Consulta para obtener los detalles de la factura
$query = "SELECT ID_Factura, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Total FROM facturas WHERE ID_Factura = $id_factura LIMIT 1";
$result = mysqli_query($conexion, $query);

// Verificar si la consulta tuvo éxito y si se encontró la factura
if ($result && mysqli_num_rows($result) > 0) {
    $factura = mysqli_fetch_assoc($result);
} else {
    echo "Factura no encontrada.";
    exit();
}

mysqli_close($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .factura-container {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 800px;
            margin: auto;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
        }
        .factura-info {
            margin-bottom: 20px;
        }
        .factura-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<div class="factura-container">
    <h1>Factura</h1>
    <div class="factura-info">
        <p><strong>ID Factura:</strong> <?php echo htmlspecialchars($factura['ID_Factura']); ?></p>
        <p><strong>Fecha de Compra:</strong> <?php echo htmlspecialchars($factura['Fecha']); ?></p>
        <p><strong>Cliente:</strong> <?php echo htmlspecialchars($factura['Nombre_cliente'] . ' ' . $factura['Apellido_cliente']); ?></p>
    </div>
    
    <h2>Detalles del Producto</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo htmlspecialchars($factura['Productos']); ?></td>
                <td><?php echo htmlspecialchars($factura['Cantidad']); ?></td>
                <td><?php echo htmlspecialchars($factura['Precio']); ?></td>
                <td><?php echo htmlspecialchars($factura['Total']); ?></td>
            </tr>
        </tbody>
    </table>
    
    <h2>Total</h2>
    <p><strong>Total:</strong> <?php echo htmlspecialchars($factura['Total']); ?></p>
</div>

</body>
</html>