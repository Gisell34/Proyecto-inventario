<?php 
include('conexion.php');

// Asegúrate de que la sesión solo se inicie una vez
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Obtener datos del usuario
$usuario = $_SESSION['usuario'];
$consulta = "SELECT c.Nombres FROM cliente c JOIN usuarios u ON c.CodigoCliente = u.Id_usuario WHERE u.Usuario = ?";
if ($stmt = $conex->prepare($consulta)) {
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta SQL para recuperar datos del usuario: " . $conex->error;
}

$facturas = [];
$mensaje_facturas = "";
$facturasPorPagina = 4; // Cambia este valor para ajustar el número de facturas por página
$totalFacturas = 0; // Inicializamos el contador de facturas

// Asegúrate de que $_SESSION['usuario'] esté definido
if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    $nombre_usuario = $_SESSION['usuario'];

    // Consulta para obtener el ID del usuario basado en el nombre de usuario
    $consulta_id_usuario = "SELECT Id_usuario FROM usuarios WHERE Usuario = ?";
    if ($stmt_id_usuario = $conex->prepare($consulta_id_usuario)) {
        $stmt_id_usuario->bind_param("s", $nombre_usuario);
        $stmt_id_usuario->execute();
        $resultado_id_usuario = $stmt_id_usuario->get_result();

        // Verificar si se obtuvo un ID de usuario
        if ($resultado_id_usuario->num_rows > 0) {
            $row_id_usuario = $resultado_id_usuario->fetch_assoc();
            $id_usuario = $row_id_usuario['Id_usuario'];

            // Si se ingresó un ID de factura en el formulario, filtrar por ese ID
            $id_factura_buscada = isset($_GET['id_factura']) ? (int)$_GET['id_factura'] : null;

            if ($id_factura_buscada) {
                // Consulta para obtener una factura específica
                $consulta_factura = "SELECT * FROM facturas WHERE Id_usuario = ? AND ID_Factura = ?";
                if ($stmt_factura = $conex->prepare($consulta_factura)) {
                    $stmt_factura->bind_param("ii", $id_usuario, $id_factura_buscada);
                    $stmt_factura->execute();
                    $resultado_factura = $stmt_factura->get_result();

                    if ($resultado_factura->num_rows > 0) {
                        while ($row_factura = $resultado_factura->fetch_assoc()) {
                            $facturas[] = $row_factura;
                        }
                    } else {
                        $mensaje_facturas = "No se encontró la factura con ID $id_factura_buscada.";
                    }

                    $stmt_factura->close();
                }
            } else {
                // Consultar el total de facturas
                $consulta_total_facturas = "SELECT COUNT(*) as total FROM facturas WHERE Id_usuario = ?";
                if ($stmt_total = $conex->prepare($consulta_total_facturas)) {
                    $stmt_total->bind_param("i", $id_usuario);
                    $stmt_total->execute();
                    $resultado_total = $stmt_total->get_result();
                    $totalFacturas = $resultado_total->fetch_assoc()['total'];
                    $stmt_total->close();
                }

                // Paginación
                $paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
                $inicio = ($paginaActual - 1) * $facturasPorPagina;
                $totalPaginas = ceil($totalFacturas / $facturasPorPagina);

                // Consulta para obtener facturas del usuario con paginación
                $consulta_facturas = "SELECT * FROM facturas WHERE Id_usuario = ? LIMIT ?, ?";
                if ($stmt_facturas = $conex->prepare($consulta_facturas)) {
                    $stmt_facturas->bind_param("iii", $id_usuario, $inicio, $facturasPorPagina);
                    $stmt_facturas->execute();
                    $resultado_facturas = $stmt_facturas->get_result();

                    // Verificar si hay facturas
                    if ($resultado_facturas->num_rows > 0) {
                        while ($row_factura = $resultado_facturas->fetch_assoc()) {
                            $facturas[] = $row_factura;
                        }
                    } else {
                        $mensaje_facturas = "No posee facturas disponibles para visualizar.";
                    }

                    $stmt_facturas->close();
                } else {
                    echo "Error en la preparación de la consulta SQL para recuperar facturas: " . $conex->error;
                }
            }
        } else {
            $mensaje_facturas = "No se encontró el ID del usuario.";
        }

        $stmt_id_usuario->close();
    } else {
        echo "Error en la preparación de la consulta SQL para recuperar el ID del usuario: " . $conex->error;
    }
} else {
    echo "Error: Nombre de usuario no definido.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mis Facturas</title>
    <style>
        #lista-facturas-usuario {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        #lista-facturas-usuario header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 20px 0;
            text-align: center;
            color: black;
            margin-bottom: 20px;
        }

        #lista-facturas-usuario h1 {
            color: #444552;
            margin: 0;
        }

        #lista-facturas-usuario {
            max-width: 1300px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #lista-facturas-usuario table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        #lista-facturas-usuario table th,
        #lista-facturas-usuario table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        #lista-facturas-usuario table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        #lista-facturas-usuario .back-link-container {
            text-align: center;
            margin: 0 auto;
        }

        #lista-facturas-usuario .back-link {
            display: inline-block;
            width: 25%;
            padding: 7px;
            margin: 0 center;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        #lista-facturas-usuario .pagination {
            text-align: center;
            margin-top: 20px;
        }

        #lista-facturas-usuario .pagination a {
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

        #lista-facturas-usuario .pagination a:hover {
            background-color: #9495b9;
        }

        #lista-facturas-usuario .pagination a.active {
            background-color: #6ab3b0;
            color: #fff;
            border: 1px solid #6ab3b0;
        }

        #lista-facturas-usuario .button {
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

        #lista-facturas-usuario .button:hover {
            background-color: #9495b9;
        }

        #lista-facturas-usuario .button-full {
            display: block;
            width: 94%;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 0px center;
            transition: background-color 0.3s;
            text-align: center;
        }

        #lista-facturas-usuario .table-button {
            padding: 10px 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 0 center;
            transition: background-color 0.3s;
            display: inline-block;
            font-weight: bold; /* Asegura que el texto esté en negrita */
        }

        #lista-facturas-usuario .table-button:hover {
            background-color: #9495b9;
        }

        #lista-facturas-usuario .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-facturas-usuario .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        #lista-facturas-usuario .search-container button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold; /* Asegura que el texto esté en negrita */
        }

        #lista-facturas-usuario .search-container button:hover {
            background-color: #9495b9;
        }
        </style>
</head>
<body>
    <div id="lista-facturas-usuario">
        <header>
            <h1>Mis Facturas</h1>
        </header>
        <div class="user-panel-facturas-usuario">
            <a href="user.php" class="back-link">Regresar</a>

            <!-- Formulario de búsqueda de factura -->
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="id_factura" placeholder="Ingrese ID de Factura">
                    <button type="submit">Buscar Factura</button>
                </form>
            </div>

            <div style="overflow-x: auto; margin: 20px 0;">
                <?php if (!empty($mensaje_facturas)): ?>
                    <p><?php echo htmlspecialchars($mensaje_facturas); ?></p>
                <?php else: ?>
                    <table class="facturas-table">
                        <thead>
                            <tr>
                                <th>ID Factura</th>
                                <th>Fecha</th>
                                <th>Nombre Cliente</th>
                                <th>Apellido Cliente</th>
                                <th>Productos</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Servicio(s)</th>
                                <th>Tipo</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturas as $factura): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($factura['ID_Factura']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Apellido_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Productos']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Cantidad']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Precio']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Nombre_servicio']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Tipo_servicio']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Precio_venta']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Total']); ?></td>
                                    <td>
                                        <a href="ver_factura.php?id=<?php echo htmlspecialchars($factura['ID_Factura']); ?>" class="table-button">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <!-- Paginación solo si no se está buscando una factura específica -->
                    <?php if (!$id_factura_buscada && $totalPaginas > 1): ?>
                        <div class="pagination">
                            <!-- Botón "Anterior" -->
                            <?php if ($paginaActual > 1): ?>
                                <a href="?pagina=<?php echo $paginaActual - 1; ?>" class="button">Anterior</a>
                            <?php else: ?>
                                <span style="margin: 0 5px; padding: 10px 16px; color: #ccc;">Anterior</span>
                            <?php endif; ?>

                            <!-- Enlaces de páginas -->
                            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                                <a href="?pagina=<?php echo $i; ?>" <?php if ($i == $paginaActual) echo 'class="active"'; ?>><?php echo $i; ?></a>
                            <?php endfor; ?>

                            <!-- Botón "Siguiente" -->
                            <?php if ($paginaActual < $totalPaginas): ?>
                                <a href="?pagina=<?php echo $paginaActual + 1; ?>" class="button">Siguiente</a>
                            <?php else: ?>
                                <span style="margin: 0 5px; padding: 10px 16px; color: #ccc;">Siguiente</span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>