<?php
include('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$serviciosPorPagina = 2;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $serviciosPorPagina;
$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

if (!empty($busqueda)) {
    $sql_servicios = "SELECT Codigo, Nombre_servicio, Tipo_servicio, Estado 
                      FROM servicios 
                      WHERE Nombre_servicio LIKE ? 
                      LIMIT ?, ?";
    $stmt = $conex->prepare($sql_servicios);
    $likeBusqueda = "%" . $busqueda . "%";
    $stmt->bind_param("sii", $likeBusqueda, $inicio, $serviciosPorPagina);
} else {
    $sql_servicios = "SELECT Codigo, Nombre_servicio, Tipo_servicio, Estado 
                      FROM servicios 
                      LIMIT ?, ?";
    $stmt = $conex->prepare($sql_servicios);
    $stmt->bind_param("ii", $inicio, $serviciosPorPagina);
}

$stmt->execute();
$resultado_servicios = $stmt->get_result();

if (!empty($busqueda)) {
    $sqlTotalServicios = "SELECT COUNT(*) as total FROM servicios WHERE Nombre_servicio LIKE ?";
    $stmtTotal = $conex->prepare($sqlTotalServicios);
    $stmtTotal->bind_param("s", $likeBusqueda);
} else {
    $sqlTotalServicios = "SELECT COUNT(*) as total FROM servicios";
    $stmtTotal = $conex->prepare($sqlTotalServicios);
}

$stmtTotal->execute();
$resultadoTotal = $stmtTotal->get_result();
$totalServicios = $resultadoTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalServicios / $serviciosPorPagina);

$stmt->close();
$stmtTotal->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Servicios</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        #lista-servicios {
            font-family: Arial, sans-serif;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        #lista-servicios header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 20px 0;
            text-align: center;
            color: white;
            margin-bottom: 20px;
        }

        #lista-servicios h1 {
            color: #444552;
            margin: 0;
        }

        #lista-servicios .user-panel-servicios {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #lista-servicios table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        #lista-servicios table th, 
        #lista-servicios table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        #lista-servicios table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        #lista-servicios .buttons-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-servicios .button {
            display: inline-block;
            width: calc(20% - 10px);
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }

        #lista-servicios .button:hover {
            background-color: #9495b9;
        }

        #lista-servicios .pagination {
            text-align: center;
            margin-top: 20px;
        }

        #lista-servicios .pagination a {
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

        #lista-servicios .pagination a:hover {
            background-color: #9495b9;
        }

        #lista-servicios .pagination a.active {
            background-color: #6ab3b0;
            color: #0e0b0b;
            border: 1px solid #6ab3b0;
        }

        #lista-servicios .button {
            display: inline-block;
            width: calc(29% - 20px);
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 10px;
            transition: background-color 0.3s;
            text-align: center;
        }

        #lista-servicios .button:hover {
            background-color: #9495b9;
        }

        #lista-servicios .button-full {
            display: block;
            width: 97%;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
            transition: background-color 0.3s;
            text-align: center;
        }

        #lista-servicios .table-button {
        padding: 10px 15px;
        background: linear-gradient(to right, #7bc0c5, #9495b9);
        color: black;
        text-decoration: none;
        border-radius: 5px;
        margin: 5px 10px;
        transition: background-color 0.3s;
        display: inline-block;
        width: calc(20% - 20px);
        }

        #lista-servicios .table-button:hover {
            background-color: #9495b9;
        }

        #lista-servicios .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-servicios .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        #lista-servicios .search-container button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        #lista-servicios .search-container button:hover {
            background-color: #9495b9;
        }

        .search-container button:hover {
            background-color: #9495b9;
            font-weight: bold;
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
    <div id="lista-servicios">
        <header>
            <h1>Lista de Servicios</h1>
        </header>

        <div class="user-panel-servicios">
            <div class="buttons-container">
                <a class="button" href="admin.php">Regresar al panel del Administrador</a>
                <a href="formulario_servicios.html" class="button">Agregar servicio</a>
            </div>

            <div class="search-container">
                <form action="lista-servicios.php" method="get">
                    <input type="text" name="busqueda" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Buscar por nombre del servicio">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <?php if ($resultado_servicios->num_rows > 0) : ?>
                <div class="list-container">
                    <table class="user-table">
                        <thead>
                            <tr>
                                <th>Código del servicio</th>
                                <th>Nombre del servicio</th>
                                <th>Tipo de servicio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado_servicios->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["Codigo"]) ?></td>
                                    <td><?= htmlspecialchars($row["Nombre_servicio"]) ?></td>
                                    <td><?= htmlspecialchars($row["Tipo_servicio"]) ?></td>
                                    <td><?= htmlspecialchars($row["Estado"]) ?></td>
                                    <td>
                                        <a href="modificar_servicio.php?codigo=<?= urlencode($row['Codigo']) ?>" class="table-button">Editar</a>
                                        <a href="eliminar_servicio.php?codigo=<?= urlencode($row['Codigo']) ?>" class="table-button" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?');">Eliminar</a>
                                        <?php if ($row["Estado"] == 'Activo') : ?>
                                            <a href="cambiar_estado_servicio.php?codigo=<?= urlencode($row['Codigo']) ?>&estado=Inactivo" class="table-button">Desactivar</a>
                                        <?php else : ?>
                                            <a href="cambiar_estado_servicio.php?codigo=<?= urlencode($row['Codigo']) ?>&estado=Activo" class="table-button">Activar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <?php if ($paginaActual > 1): ?>
                        <a href="lista-servicios.php?pagina=<?= $paginaActual - 1 ?>&busqueda=<?= urlencode($busqueda) ?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="lista-servicios.php?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>" class="<?= $i == $paginaActual ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="lista-servicios.php?pagina=<?= $paginaActual + 1 ?>&busqueda=<?= urlencode($busqueda) ?>">Siguiente</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <p>No se encontraron servicios.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conex->close();
?>