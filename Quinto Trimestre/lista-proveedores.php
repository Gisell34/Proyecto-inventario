<?php
include('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$proveedoresPorPagina = 3;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $proveedoresPorPagina;

$busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';

$mensaje = "";
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    $tipo_mensaje = $_SESSION['tipo_mensaje'];
    unset($_SESSION['mensaje'], $_SESSION['tipo_mensaje']);
}

$sql_proveedores = "SELECT Codigoproveedor, Nombre_proveedor, Telefono, Nit, Direccion, Correo_electronico 
                    FROM proveedor 
                    WHERE Nombre_proveedor LIKE ? 
                    LIMIT ?, ?";
$stmt = $conex->prepare($sql_proveedores);
$likeBusqueda = "%$busqueda%";
$stmt->bind_param("sii", $likeBusqueda, $inicio, $proveedoresPorPagina);
$stmt->execute();
$resultado_proveedores = $stmt->get_result();

$sqlTotalProveedores = "SELECT COUNT(*) as total FROM proveedor WHERE Nombre_proveedor LIKE ?";
$stmtTotal = $conex->prepare($sqlTotalProveedores);
$stmtTotal->bind_param("s", $likeBusqueda);
$stmtTotal->execute();
$resultadoTotal = $stmtTotal->get_result();
$totalProveedores = $resultadoTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalProveedores / $proveedoresPorPagina);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Lista de Proveedores</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        #lista-proveedores {
            font-family: Arial, sans-serif;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        #lista-proveedores header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 30px 0;
            text-align: center;
            color: black;
            margin-bottom: 20px;
        }

        #lista-proveedores h1 {
            color: #444552;
            margin: 0;
        }

        #lista-proveedores .user-panel-proveedores {
            max-width: 1100px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #lista-proveedores table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        #lista-proveedores table th,
        #lista-proveedores table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        #lista-proveedores table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        #lista-proveedores .buttons-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-proveedores .button {
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

        #lista-proveedores .button:hover {
            background-color: #9495b9;
        }

        #lista-proveedores .pagination {
            text-align: center;
            margin-top: 20px;
        }

        #lista-proveedores .pagination a {
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

        #lista-proveedores .pagination a:hover {
            background-color: #9495b9;
        }

        #lista-proveedores .pagination a.active {
            background-color: #6ab3b0;
            color: #0c0c0f;
            border: 1px solid #6ab3b0;
        }

        #lista-proveedores .button {
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

        #lista-proveedores .button:hover {
            background-color: #9495b9;
        }

        #lista-proveedores .button-full {
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

        #lista-proveedores .table-button {
            padding: 10px 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 10px;
            transition: background-color 0.3s;
            display: inline-block;
            width: calc(50% - 30px);
        }

        #lista-proveedores .table-button:hover {
            background-color: #9495b9;
        }

        #lista-proveedores .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-proveedores .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        #lista-proveedores .search-container button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        #lista-proveedores .search-container button:hover {
            background-color: #9495b9;
        }

        .search-container button:hover {
            background-color: #9495b9;
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

        .mensaje-flotante {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
            color: #fff;
            background-color: #4caf50;
            /* Verde por defecto para éxito */
            z-index: 1000;
            transition: opacity 0.5s ease;
        }

        .mensaje-flotante.error {
            background-color: #dc3545;
            /* Rojo para errores */
        }
    </style>

</head>

<body>
    <div id="lista-proveedores">
        <header>
            <h1>Lista de Proveedores</h1>
        </header>

        <div class="user-panel-proveedores">
            <div class="buttons-container">
                <a class="button" href="admin.php">Regresar al panel del Administrador</a>
                <a href="formulario_proveedores.html" class="button">Agregar proveedor</a>
            </div>

            <div class="search-container">
                <form action="lista-proveedores.php" method="get">
                    <input type="text" name="busqueda" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Buscar por nombre del proveedor">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <?php if ($resultado_proveedores->num_rows > 0) : ?>
                <div class="list-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Código del proveedor</th>
                                <th>Nombre del proveedor</th>
                                <th>Teléfono</th>
                                <th>NIT</th>
                                <th>Dirección</th>
                                <th>Correo electrónico</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $resultado_proveedores->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row["Codigoproveedor"]) ?></td>
                                    <td><?= htmlspecialchars($row["Nombre_proveedor"]) ?></td>
                                    <td><?= htmlspecialchars($row["Telefono"]) ?></td>
                                    <td><?= htmlspecialchars($row["Nit"]) ?></td>
                                    <td><?= htmlspecialchars($row["Direccion"]) ?></td>
                                    <td><?= htmlspecialchars($row["Correo_electronico"]) ?></td>
                                    <td>
                                        <a href="editar_proveedor.php?codigo=<?= $row['Codigoproveedor'] ?>" class="table-button">Editar</a>
                                        <a href="eliminar_proveedor.php?codigo=<?= $row['Codigoproveedor'] ?>" class="table-button" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    <?php if ($paginaActual > 1): ?>
                        <a href="lista-proveedores.php?pagina=<?= $paginaActual - 1 ?>&busqueda=<?= urlencode($busqueda) ?>">Anterior</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                        <a href="lista-proveedores.php?pagina=<?= $i ?>&busqueda=<?= urlencode($busqueda) ?>" class="<?= $i == $paginaActual ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>

                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a href="lista-proveedores.php?pagina=<?= $paginaActual + 1 ?>&busqueda=<?= urlencode($busqueda) ?>">Siguiente</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <p>No se encontraron proveedores.</p>
            <?php endif; ?>
        </div>
</body>

</html>