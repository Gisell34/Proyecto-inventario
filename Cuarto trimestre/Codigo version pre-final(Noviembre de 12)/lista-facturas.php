<?php
include('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$facturasPorPagina = 3;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $facturasPorPagina;

$idFactura = isset($_GET['id_factura']) ? intval($_GET['id_factura']) : null;

if ($idFactura) {
    $sql_facturas = "SELECT ID_Factura, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Nombre_servicio, Tipo_servicio, Precio_venta, Total 
                     FROM facturas 
                     WHERE ID_Factura = ?";
    $stmt = $conex->prepare($sql_facturas);
    $stmt->bind_param("i", $idFactura);
} else {
    $sql_facturas = "SELECT ID_Factura, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Nombre_servicio, Tipo_servicio, Precio_venta, Total 
                     FROM facturas 
                     ORDER BY Fecha DESC 
                     LIMIT ?, ?";
    $stmt = $conex->prepare($sql_facturas);
    $stmt->bind_param("ii", $inicio, $facturasPorPagina);
}

$stmt->execute();
$resultado_facturas = $stmt->get_result();
if (!$idFactura) {
    $sqlTotalFacturas = "SELECT COUNT(*) as total FROM facturas";
    $resultadoTotal = $conex->query($sqlTotalFacturas);
    $totalFacturas = $resultadoTotal->fetch_assoc()['total'];
    $totalPaginas = ceil($totalFacturas / $facturasPorPagina);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Facturas</title>
    <style>
        #lista-facturas {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
    }

        #lista-facturas header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 20px 0;
            text-align: center;
            color: black;
            margin-bottom: 20px;
    }

        #lista-facturas h1 {
            color: #444552;
            margin: 0;
    }

        #lista-facturas .user-panel-facturas {
            max-width: 1300px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #lista-facturas table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        #lista-facturas table th, 
        #lista-facturas table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        #lista-facturas table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        #lista-facturas .back-link-container {
            text-align: center;
            margin: 20px 0;
        }

        #lista-facturas .back-link {
            display: inline-block;
            width: 98%;
            padding: 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        #lista-facturas .pagination {
            text-align: center;
            margin-top: 20px;
        }

        #lista-facturas .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 10px 16px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border: 1px solid #ccc;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        #lista-facturas .pagination a:hover {
            background-color: #9495b9;
        }

        #lista-facturas .pagination a.active {
            background-color: #6ab3b0;
            color: #0e0b0b;
            border: 1px solid #6ab3b0;
        }

        #lista-facturas .button {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .button:hover {
            background-color: #9495b9;
        }

        #lista-facturas .button:hover {
            background-color: #9495b9;
        }

        #lista-facturas .button-full {
            display: block;
            width: 100%;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            transition: background-color 0.3s;
            text-align: center;
        }

        #lista-facturas .table-button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
            display: inline-block;
        }

        #lista-facturas .table-button:hover {
            background-color: #9495b9;
        }

        #lista-facturas .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-facturas .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        #lista-facturas .search-container button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #lista-facturas .search-container button:hover {
            background-color: #9495b9;
        }
</style>
</head>
<body>
    <div id="lista-facturas">
        <header>
            <h1>Lista de Facturas</h1>
        </header>

        <div class="user-panel-facturas">
            <div class="back-link-container">
                <a href="admin.php" class="button">Regresar al Administrador</a>
                <a href="reporte_mensual.php" class="button">Generar reporte</a>
            </div>
            
            <div class="search-container">
                <form method="GET" action="lista-facturas.php">
                    <input type="text" name="id_factura" placeholder="Buscar por ID de Factura" value="<?= htmlspecialchars($idFactura) ?>">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <div style="overflow-x: auto; margin: 20px 0;">
                <table class="user-table">
                    <thead>
                        <tr>
                            <th>ID Factura</th>
                            <th>Fecha</th>
                            <th>Nombre Cliente</th>
                            <th>Apellido Cliente</th>
                            <th>Productos</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Servicio</th>
                            <th>Tipo</th>
                            <th>Precio Venta</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($resultado_facturas->num_rows > 0): ?>
                            <?php while ($row = $resultado_facturas->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["ID_Factura"]) ?></td>
                                    <td><?= htmlspecialchars($row["Fecha"]) ?></td>
                                    <td><?= htmlspecialchars($row["Nombre_cliente"]) ?></td>
                                    <td><?= htmlspecialchars($row["Apellido_cliente"]) ?></td>
                                    <td><?= htmlspecialchars($row["Productos"]) ?></td>
                                    <td><?= htmlspecialchars($row["Cantidad"]) ?></td>
                                    <td><?= htmlspecialchars($row["Precio"]) ?></td>
                                    <td><?= htmlspecialchars($row["Nombre_servicio"]) ?></td>
                                    <td><?= htmlspecialchars($row["Tipo_servicio"]) ?></td>
                                    <td><?= htmlspecialchars($row["Precio_venta"]) ?></td>
                                    <td><?= htmlspecialchars($row["Total"]) ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="11">No se encontraron facturas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!$idFactura && $totalPaginas > 1): ?>
                <div class="pagination">
                    <?php if ($paginaActual > 1): ?>
                        <a href="lista-facturas.php?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="lista-facturas.php?pagina=<?= $i ?>" class="<?= $paginaActual == $i ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="lista-facturas.php?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($resultado_facturas->num_rows > 0): ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>