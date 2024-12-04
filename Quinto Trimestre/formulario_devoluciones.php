<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('database.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function obtenerFacturaPorId($conn, $id)
{
    $sql = "SELECT * FROM facturas WHERE ID_Factura=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

$mensaje = ""; // Variable para mensajes

// Buscar factura
if (isset($_POST['buscar_factura'])) {
    $id_factura = intval($_POST['id_factura']);
    $facturaBuscada = obtenerFacturaPorId($conn, $id_factura);
    if (!$facturaBuscada) {
        $mensaje = "No se encontró ninguna factura con ese ID.";
    }
}

// Editar factura
if (isset($_POST['editar_factura'])) {
    $id_factura = intval($_POST['id_factura']);
    $productos = $_POST['productos'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $nombre_servicio = $_POST['nombre_servicio'] ?: NULL;
    $tipo_servicio = $_POST['tipo_servicio'] ?: NULL;
    $precio_venta = floatval($_POST['precio_venta']) ?: NULL;
    $total = floatval($_POST['total']);

    // Preparar y ejecutar consulta
    $sql = "UPDATE facturas 
            SET Productos=?, Cantidad=?, Precio=?, Nombre_servicio=?, Tipo_servicio=?, Precio_venta=?, Total=? 
            WHERE ID_Factura=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssdi", $productos, $cantidad, $precio, $nombre_servicio, $tipo_servicio, $precio_venta, $total, $id_factura);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $mensaje = "La factura ha sido actualizada correctamente.";
            $facturaBuscada = obtenerFacturaPorId($conn, $id_factura); // Refrescar datos
        } else {
            $mensaje = "No se realizaron cambios en la factura.";
        }
    } else {
        $mensaje = "Error al actualizar la factura: " . $stmt->error;
    }
}

// Paginación
$facturasPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $facturasPorPagina;
$sql = "SELECT * FROM facturas LIMIT $inicio, $facturasPorPagina";
$resultado_facturas = $conn->query($sql);
$sqlTotalFacturas = "SELECT COUNT(*) as total FROM facturas";
$resultadoTotal = $conn->query($sqlTotalFacturas);
$totalFacturas = $resultadoTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalFacturas / $facturasPorPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 20px 0;
            text-align: center;
            color: black;
        }

        h1 {
            color: #444552;
            margin: 0;
        }

        .user-panel-productos, .user-panel-formulario {
            max-width: 1100px;
            margin: 10px auto;
            padding: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .boton-guardar {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            font-weight: bold;
        }

        .boton-guardar:hover {
            background-color: #45a049;
        }
        
        .user-panel-formulario table {
            width: 40%;
            border-collapse: collapse;
        }

        .user-panel-formulario table td {
            padding: 8px;
            text-align: left;
        }

        .user-panel-formulario label {
            font-weight: bold;
            font-size: 14px;
        }

        /* Estilo para los campos de entrada */
        .user-panel-formulario input[type="text"],
        .user-panel-formulario input[type="number"] {
            width: 80%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 12px;
            font-weight: bold;
        }
                
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background: white;
        }

        table th,
        table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ccc;
        }

        table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a,
        .button,
        .back-to-admin,
        .search-container button {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
            text-align: center;
            font-weight: bold;
        }

        .pagination a:hover,
        .back-to-admin:hover,
        .search-container button:hover {
            background-color: #9495b9;
        }

        .pagination a.active {
            background-color: #6ab3b0;
            color: #0e0b0b;
            border: 1px solid #6ab3b0;
        }

        .search-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        form {
            margin-bottom: 20px;
        }

        .back-link-container {
            text-align: center;
        }

        .add-button {
            margin: 10px 0;
        }
    </style>
</head>

<body>
    <header>
        <div class="user-panel-productos">
            <h1>Editar Factura</h1>
        </div>
    </header>

    <form method="post" action="" style="text-align: center;">
        <div class="search-container" style="display: inline-flex; flex-direction: row; align-items: center; justify-content: center; margin-bottom: 10px;">
            <label for="id_factura" style="margin-right: 10px;">ID de Factura:</label>
            <input type="text" id="id_factura" name="id_factura" required style="margin-right: 10px;">
            <button type="submit" name="buscar_factura">Buscar Factura</button>
        </div>
        <div class="back-link-container" style="margin-top: 10px;">
            <a href="admin.php" class="back-to-admin">Volver al panel del Administrador</a>
        </div>
    </form>

    <?php if ($mensaje): ?>
        <p style="text-align: center; color: green;"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>

    <?php if (isset($facturaBuscada) && $facturaBuscada): ?>
        <h2 style="text-align: center;">Factura Encontrada</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Nombre Servicio</th>
                    <th>Tipo del servicio</th>
                    <th>Precio Venta</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= htmlspecialchars($facturaBuscada["ID_Factura"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Fecha"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Productos"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Cantidad"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Precio"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Nombre_servicio"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Tipo_servicio"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Precio_venta"]) ?></td>
                    <td><?= htmlspecialchars($facturaBuscada["Total"]) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="user-panel-formulario">
            <h2 style="text-align: center;">Editar Información de la Factura</h2>
            <form method="post" action="">
                <input type="hidden" name="id_factura" value="<?= $facturaBuscada['ID_Factura'] ?>">
                <table>
                    <tr>
                        <td><label>Productos:</label></td>
                        <td><input type="text" name="productos" value="<?= htmlspecialchars($facturaBuscada['Productos']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Cantidad:</label></td>
                        <td><input type="number" name="cantidad" value="<?= $facturaBuscada['Cantidad'] ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Precio:</label></td>
                        <td><input type="number" step="0.01" name="precio" value="<?= $facturaBuscada['Precio'] ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Nombre Servicio:</label></td>
                        <td><input type="text" name="nombre_servicio" value="<?= htmlspecialchars($facturaBuscada['Nombre_servicio']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Tipo de servicio:</label></td>
                        <td><input type="text" name="tipo_servicio" value="<?= htmlspecialchars($facturaBuscada['Tipo_servicio']) ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Precio Venta:</label></td>
                        <td><input type="number" step="0.01" name="precio_venta" value="<?= $facturaBuscada['Precio_venta'] ?>" required></td>
                    </tr>
                    <tr>
                        <td><label>Total:</label></td>
                        <td><input type="number" step="0.01" name="total" value="<?= $facturaBuscada['Total'] ?>" required></td>
                    </tr>
                </table>
                <div style="text-align: center; margin-top: 15px;">
                    <button type="submit" name="editar_factura" class="boton-guardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    <?php elseif (isset($id_factura)): ?>
        <p style="text-align: center;">No se encontró ninguna factura con ese ID.</p>
    <?php endif; ?>

    <div class="user-panel-productos">
        <h2>Todas las Facturas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha</th>
                    <th>Productos</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Nombre del servicio</th>
                    <th>Tipo del servicio</th>
                    <th>Precio de venta</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($factura = $resultado_facturas->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($factura["ID_Factura"]) ?></td>
                        <td><?= htmlspecialchars($factura["Fecha"]) ?></td>
                        <td><?= htmlspecialchars($factura["Productos"]) ?></td>
                        <td><?= htmlspecialchars($factura["Cantidad"]) ?></td>
                        <td><?= htmlspecialchars($factura["Precio"]) ?></td>
                        <td><?= htmlspecialchars($factura["Nombre_servicio"]) ?></td>
                        <td><?= htmlspecialchars($factura["Tipo_servicio"]) ?></td>
                        <td><?= htmlspecialchars($factura["Precio_venta"]) ?></td>
                        <td><?= htmlspecialchars($factura["Total"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>

</html>