<?php
include 'conexion.php';

$id_factura = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_factura > 0) {
    $consulta = "SELECT f.ID_Factura, f.Fecha, f.Nombre_cliente, f.Apellido_cliente, f.Productos, f.Cantidad, f.Precio, f.Total, f.Nombre_servicio, f.Tipo_servicio, f.Precio_venta, f.metodo_pago,
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
            exit;
        }

        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta SQL: " . $conex->error;
        exit;
    }
} else {
    echo "ID de factura no válido.";
    exit;
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
    margin: 3px 0;
}

h2 {
    margin-top: 30px;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px;
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

.th-encabezado {
    background: linear-gradient(to right, #7bc0c5, #9495b9);
    font-weight: bold;
}

.total {
    font-weight: bold;
    font-size: 1.2em;
    background: linear-gradient(to right, #7bc0c5, #9495b9);
}

.btn-regresar {
    display: block;
    width: 200px;
    margin: 10px auto;
    padding: 10px;
    text-align: center;
    color: #fff;
    background: linear-gradient(to right, #7bc0c5, #9495b9);
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.btn-regresar:hover {
    background: linear-gradient(to right, #7bc0c5, #9495b9);
}
    </style>
</head>
<body>
    <a href="lista-facturas-usuario.php" class="btn-regresar">Regresar a Facturas</a>
    <h1>Detalles de la Factura</h1>
    <div class="factura-container">
        <h2>Información del Cliente</h2>
        <table>
            <tr>
                <th class="th-encabezado">ID Factura</th>
                <td><?php echo htmlspecialchars($factura['ID_Factura']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Fecha</th>
                <td><?php echo htmlspecialchars($factura['Fecha']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Nombre Cliente</th>
                <td><?php echo htmlspecialchars($factura['Nombre_cliente']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Apellido Cliente</th>
                <td><?php echo htmlspecialchars($factura['Apellido_cliente']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Cédula</th>
                <td><?php echo htmlspecialchars($factura['Cedula']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Teléfono</th>
                <td><?php echo htmlspecialchars($factura['Telefono']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Dirección</th>
                <td><?php echo htmlspecialchars($factura['Direccion']); ?></td>
            </tr>
            <tr>
                <th class="th-encabezado">Correo Electrónico</th>
                <td><?php echo htmlspecialchars($factura['Correo_electronico']); ?></td>
            </tr>
        </table>

        <h2>Información de la Factura</h2>
        <table>
            <tr>
                <th class="th-encabezado">Producto</th>
                <th class="th-encabezado">Cantidad</th>
                <th class="th-encabezado">Precio Unitario</th>
                <th class="th-encabezado">Total Producto</th>
            </tr>
            <?php
            $productos = explode(',', $factura['Productos']);
            $cantidades = explode(',', $factura['Cantidad']);
            $precios = explode(',', $factura['Precio']);

            $max_items = max(count($productos), count($cantidades), count($precios));
            $total_factura = 0; // Variable para almacenar el total de los productos

            // Recorrer cada producto para calcular el total de cada uno y acumularlo
            for ($i = 0; $i < $max_items; $i++) {
                $producto = isset($productos[$i]) ? htmlspecialchars($productos[$i]) : 'N/A';
                $cantidad = isset($cantidades[$i]) ? floatval($cantidades[$i]) : 0; // Asegurarse de que sea un número
                $precio = isset($precios[$i]) ? floatval($precios[$i]) : 0; // Asegurarse de que sea un número

                // Calcular el total por producto (cantidad * precio unitario)
                $total_producto = $cantidad * $precio;

                // Sumar el total del producto a la variable acumuladora
                $total_factura += $total_producto;

                echo "<tr>";
                echo "<td>" . $producto . "</td>";
                echo "<td>" . $cantidad . "</td>";
                echo "<td>" . $precio . "</td>";
                echo "<td>" . number_format($total_producto, 2) . "</td>"; // Mostrar el total por producto
                echo "</tr>";
            }
            ?>

            <!-- Mostrar el total acumulado de los productos -->
            <tr>
                <th colspan="3" class="total">Total de los Productos</th>
                <td class="total"><?php echo number_format($total_factura, 2); ?></td>
            </tr>
        </table>

        <?php if (!empty($factura['Nombre_servicio']) && !empty($factura['Tipo_servicio'])): ?>
            <h2>Información del Servicio</h2>
            <table>
                <tr>
                    <th class="th-encabezado">Servicio(s)</th>
                    <td><?php echo htmlspecialchars($factura['Nombre_servicio']); ?></td>
                </tr>
                <tr>
                    <th class="th-encabezado">Tipo de Servicio</th>
                    <td><?php echo htmlspecialchars($factura['Tipo_servicio']); ?></td>
                </tr>
                <tr>
                    <th class="th-encabezado">Precio del Servicio</th>
                    <td><?php echo htmlspecialchars($factura['Precio_venta']); ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if ($total_factura > 0): ?>
            <table>
                <tr>
                    <th colspan="2" class="total">Total a pagar</th>
                    <td><?php echo number_format($factura['Total'], 2); ?></td> <!-- Llamamos el total desde la base de datos -->
                </tr>
            </table>
        <?php endif; ?>

        <table>
            <tr>
                <th colspan="2" class="metodo_pago">Método de pago</th>
                <td><?php echo htmlspecialchars($factura['metodo_pago']); ?></td>
            </tr>
        </table>
    </div>
</body>
</html>