<?php
include 'conexion.php'; // Asegúrate de incluir tu archivo de conexión

// Recuperar el ID de la factura desde la URL
$id_factura = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_factura > 0) {
    // Consulta para obtener detalles de la factura y la información del cliente
    $consulta = "SELECT f.ID_Factura, f.Fecha, f.Nombre_cliente, f.Apellido_cliente, f.Productos, f.Cantidad, f.Precio, f.Total, f.Nombre_servicio, f.Tipo_servicio, f.Precio_venta,
               c.Cedula, c.Correo_electronico, c.Direccion, c.Telefono
        FROM facturas f
        INNER JOIN cliente c ON f.Id_usuario = c.CodigoCliente
        WHERE f.ID_Factura = ?";

    if ($stmt = $conex->prepare($consulta)) {
        $stmt->bind_param("i", $id_factura);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $factura = $resultado->fetch_assoc();
        } else {
            echo "Factura no encontrada.";
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta SQL: " . $conex->error;
    }
} else {
    echo "ID de factura no válido.";
}
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
        .btn-regresar {
            display: block;
            width: 200px;
            margin: 10px auto;
            padding: 10px;
            text-align: center;
            color: #fff;
            background-color: #007bff; /* Azul */
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-regresar:hover {
            background-color: #0056b3; /* Azul más oscuro */
        }
    </style>
</head>
<body>
    <a href="user.php" class="btn-regresar">Regresar a Facturas</a>
    <h1>Detalles de la Factura</h1>
    <?php if (!empty($factura)): ?>
        <div class="factura-container">
            <h2>Información del Cliente</h2>
            <table>
                <tr>
                    <th>ID Factura</th>
                    <td><?php echo htmlspecialchars($factura['ID_Factura']); ?></td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <td><?php echo htmlspecialchars($factura['Fecha']); ?></td>
                </tr>
                <tr>
                    <th>Nombre Cliente</th>
                    <td><?php echo htmlspecialchars($factura['Nombre_cliente']); ?></td>
                </tr>
                <tr>
                    <th>Apellido Cliente</th>
                    <td><?php echo htmlspecialchars($factura['Apellido_cliente']); ?></td>
                </tr>
                <tr>
                    <th>Cédula</th>
                    <td><?php echo htmlspecialchars($factura['Cedula']); ?></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><?php echo htmlspecialchars($factura['Telefono']); ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><?php echo htmlspecialchars($factura['Direccion']); ?></td>
                </tr>
                <tr>
                    <th>Correo Electrónico</th>
                    <td><?php echo htmlspecialchars($factura['Correo_electronico']); ?></td>
                </tr>
            </table>

            <h2>Información de la Factura</h2>
            <table>
                <tr>
                    <th>Productos</th>
                    <td><?php echo nl2br(htmlspecialchars($factura['Productos'])); ?></td>
                </tr>
                <tr>
                    <th>Cantidad</th>
                    <td><?php echo htmlspecialchars($factura['Cantidad']); ?></td>
                </tr>
                <tr>
                    <th>Precio</th>
                    <td><?php echo htmlspecialchars($factura['Precio']); ?></td>
                </tr>

                <!-- Solo mostrar los campos de servicios si existen -->
                <?php if (!empty($factura['Nombre_servicio']) && !empty($factura['Tipo_servicio'])): ?>
                    <tr>
                        <th>Servicio(s)</th>
                        <td><?php echo htmlspecialchars($factura['Nombre_servicio']); ?></td>
                    </tr>
                    <tr>
                        <th>Tipo de Servicio</th>
                        <td><?php echo htmlspecialchars($factura['Tipo_servicio']); ?></td>
                    </tr>
                    <tr>
                        <th>Precio del Servicio</th>
                        <td><?php echo htmlspecialchars($factura['Precio_venta']); ?></td>
                    </tr>
                <?php endif; ?>
                
                <tr>
                    <th>Total</th>
                    <td><?php echo htmlspecialchars($factura['Total']); ?></td>
                </tr>
            </table>
        </div>
    <?php else: ?>
        <p>No se encontraron detalles de la factura.</p>
    <?php endif; ?>
</body>
</html>