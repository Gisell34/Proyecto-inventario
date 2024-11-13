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

/* Espacio entre secciones */
h2 {
    margin-top: 30px;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 10px; /* Margen entre tablas */
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

/* Nuevos estilos para las celdas de encabezado */
.th-encabezado {
    background: linear-gradient(to right, #7bc0c5, #9495b9); /* Color claro para encabezados */
    font-weight: bold;
}

/* Estilo para total */
.total {
    font-weight: bold;
    font-size: 1.2em;
    background: linear-gradient(to right, #7bc0c5, #9495b9); /* Color claro para el total */
}

.btn-regresar {
    display: block;
    width: 200px;
    margin: 10px auto;
    padding: 10px;
    text-align: center;
    color: #fff;
    background: linear-gradient(to right, #7bc0c5, #9495b9); /* Azul */
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}

.btn-regresar:hover {
    background: linear-gradient(to right, #7bc0c5, #9495b9); /* Azul más oscuro */
}
    </style>
</head>
<body>
    <a href="lista-facturas-usuario.php" class="btn-regresar">Regresar a Facturas</a>
    <h1>Detalles de la Factura</h1>
    <div class="factura-container">
<!-- Información del Cliente -->
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

<!-- Información de la Factura -->
<h2>Información de la Factura</h2>
<table>
    <tr>
        <th class="th-encabezado">Producto</th>
        <th class="th-encabezado">Cantidad</th>
        <th class="th-encabezado">Precio Unitario</th>
    </tr>
    <?php
    // Separar los productos, cantidades y precios
    $productos = explode(',', $factura['Productos']);
    $cantidades = explode(',', $factura['Cantidad']);
    $precios = explode(',', $factura['Precio_venta']);
    
    $max_items = max(count($productos), count($cantidades), count($precios));
    
    for ($i = 0; $i < $max_items; $i++) {
        $producto = isset($productos[$i]) ? htmlspecialchars($productos[$i]) : 'N/A';
        $cantidad = isset($cantidades[$i]) ? htmlspecialchars($cantidades[$i]) : 'N/A';
        $precio = isset($precios[$i]) ? htmlspecialchars($precios[$i]) : 'N/A';
    
        echo "<tr>";
        echo "<td>" . $producto . "</td>";
        echo "<td>" . $cantidad . "</td>";
        echo "<td>" . $precio . "</td>";
        echo "</tr>";
    }
    ?>
</table>

<!-- Servicios -->
<h2></h2>
<table>
    <?php if (!empty($factura['Nombre_servicio']) && !empty($factura['Tipo_servicio'])): ?>
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
        <table>
        <!-- Total a pagar -->
<table>
    <tr>
        <th colspan="2" class="total">Total a pagar</th>
        <td><?php echo htmlspecialchars($factura['Total']); ?></td>
    </tr>
</table>
    <?php endif; ?>
</table>
    </div>
</body>
</html>