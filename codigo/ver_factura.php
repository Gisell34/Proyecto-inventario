<?php
session_start();

if (!isset($_GET['id'])) {
    echo "ID de factura no proporcionado.";
    exit();
}

include('conexion.php');
$factura_id = $_GET['id'];

// Obtener datos de la factura
$sql = "SELECT * FROM factura WHERE Codigofactura = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $factura_id);
$stmt->execute();
$resultado = $stmt->get_result();
$factura = $resultado->fetch_assoc();

// Obtener detalles de la factura
$sql = "SELECT df.*, p.Nombre_producto FROM detalle_factura df INNER JOIN producto p ON df.Codigoproducto = p.Codigoproducto WHERE df.Factura_id = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param("i", $factura_id);
$stmt->execute();
$detalles = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Factura #<?php echo $factura_id; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            padding: 10px 0;
            text-align: center;
            color: white;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #899da4;
        }

        .admin-panel {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-panel h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-panel a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .admin-panel a:hover {
            background-color: #899da4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
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
    <header>
        <h1>Factura #<?php echo $factura_id; ?></h1>
    </header>
    <div class="admin-panel">
        <p>Cliente: <?php echo $factura['Codigocliente']; ?></p>
        <p>Total: $<?php echo $factura['Total']; ?></p>
        <p>IVA: $<?php echo $factura['Iva']; ?></p>
        <p>Unidad de Valor: $<?php echo $factura['Unidad_valor']; ?></p>
        <h2>Detalles de la Factura</h2>
        <table>
            <tr>
                <th>Nombre del Producto</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Total</th>
            </tr>
            <?php while($detalle = $detalles->fetch_assoc()): ?>
            <tr>
                <td><?php echo $detalle['Nombre_producto']; ?></td>
                <td><?php echo $detalle['Cantidad']; ?></td>
                <td><?php echo $detalle['Precio']; ?></td>
                <td><?php echo $detalle['Cantidad'] * $detalle['Precio']; ?></td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="admin.php#facturas">Volver a la lista de facturas</a>
    </div>
</body>
</html>