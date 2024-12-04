<?php
include('conexion.php');


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_POST['eliminar_usuario']) && isset($_POST['id_usuario'])) {
    $id_usuario = $_POST['id_usuario'];
    try {
        
        $sql_eliminar_usuario = "DELETE FROM usuarios WHERE Id_usuario = ?";
        $stmt = $conex->prepare($sql_eliminar_usuario);
        $stmt->bind_param("i", $id_usuario);

       
        if ($stmt->execute()) {
            echo "<script>alert('Usuario eliminado correctamente');</script>";
        } else {
            throw new Exception('Error al eliminar el usuario');
        }
    } catch (Exception $e) {
        
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}


if (isset($_POST['cambiar_estado']) && isset($_POST['id_usuario']) && isset($_POST['estado'])) {
    $id_usuario = $_POST['id_usuario'];
    $nuevo_estado = $_POST['estado'] == 'Habilitado' ? 'Deshabilitado' : 'Habilitado';
    $sql_cambiar_estado = "UPDATE usuarios SET estado = ? WHERE Id_usuario = ?";
    $stmt = $conex->prepare($sql_cambiar_estado);
    $stmt->bind_param("si", $nuevo_estado, $id_usuario);
    if ($stmt->execute()) {
        echo "<script>alert('Estado del usuario cambiado correctamente');</script>";
    } else {
        echo "<script>alert('Error al cambiar el estado del usuario');</script>";
    }
    $stmt->close();
}


$usuariosPorPagina = 3;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $usuariosPorPagina;
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

$sql_usuarios = "SELECT Id_usuario, Usuario, rol, estado FROM usuarios WHERE Usuario LIKE ? LIMIT ?, ?";
$stmt = $conex->prepare($sql_usuarios);
$busqueda_param = "%" . $busqueda . "%";
$stmt->bind_param("sii", $busqueda_param, $inicio, $usuariosPorPagina);
$stmt->execute();
$resultado_usuarios = $stmt->get_result();

$sqlTotalUsuarios = "SELECT COUNT(*) as total FROM usuarios WHERE Usuario LIKE ?";
$stmtTotal = $conex->prepare($sqlTotalUsuarios);
$stmtTotal->bind_param("s", $busqueda_param);
$stmtTotal->execute();
$resultadoTotal = $stmtTotal->get_result();
$totalUsuarios = $resultadoTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalUsuarios / $usuariosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>
    <style>
       body { 
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        #lista-usuarios {
            font-family: Arial, sans-serif;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        #lista-usuarios header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 20px 0;
            text-align: center;
            color: black;
            margin-bottom: 20px;
        }

        #lista-usuarios h1 {
            color: #444552;
            margin: 5px;
        }

        #lista-usuarios .user-panel-usuarios {
            max-width: 1100px;
            margin: 5px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #lista-usuarios table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        #lista-usuarios table th, 
        #lista-usuarios table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        #lista-usuarios table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        #lista-usuarios .back-link-container {
            text-align: center;
            margin: 20px 0;
        }

        #lista-usuarios .back-link {
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

        #lista-usuarios .pagination {
            text-align: center;
            margin-top: 20px;
        }

        #lista-usuarios .pagination a {
            display: inline-block;
            margin: 0 5px;
            padding: 10px 16px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border: 1px solid #9495b9;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        #lista-usuarios .pagination a:hover {
            background-color: #9495b9;
        }

        #lista-usuarios .pagination a.active {
            background-color: #6ab3b0;
            color: #0e0b0b;
            border: 1px solid #6ab3b0;
        }

        #lista-usuarios .button {
            display: inline-block;
            width: calc(99% - 20px);
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px 0;
            transition: background-color 0.3s;
            text-align: center;
            font-weight: bold;
        }

        #lista-usuarios .button:hover {
            background-color: #0e0b0b;
        }

        #lista-usuarios .button-full {
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
            font-weight: bold;
        }

        #lista-usuarios .table-button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
            display: inline-block;
            width: calc(100% - 10px);
            font-weight: bold;
        }

        #lista-usuarios .table-button:hover {
            background-color: #0e0b0b;
        }

        #lista-usuarios .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        #lista-usuarios .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        #lista-usuarios .search-container button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #lista-usuarios .search-container button:hover {
            background-color: #9495b9;
        }
        </style>
</head>
<body>
    <div id="lista-usuarios">
        <header>
            <h1>Lista de usuarios</h1>
        </header>

        <div class="user-panel-usuarios">
            <a class="back-link" href="admin.php">Regresar al panel del Administrador</a>

            <div class="search-container">
                <form action="lista-usuarios.php" method="get">
                    <input type="text" name="busqueda" value="<?= htmlspecialchars($busqueda) ?>" placeholder="Buscar por nombre de usuario">
                    <button type="submit">Buscar</button>
                </form>
            </div>

            <?php if ($resultado_usuarios->num_rows > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado_usuarios->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row["Id_usuario"]) ?></td>
                                <td><?= htmlspecialchars($row["Usuario"]) ?></td>
                                <td><?= htmlspecialchars($row["rol"]) ?></td>
                                <td><?= htmlspecialchars($row["estado"]) ?></td>
                                <td>
                                    <form action="lista-usuarios.php?pagina=<?= $paginaActual ?>&busqueda=<?= htmlspecialchars($busqueda) ?>" method="post" style="display:inline-block;">
                                        <input type="hidden" name="id_usuario" value="<?= $row['Id_usuario'] ?>">
                                        <button class="table-button" type="submit" name="eliminar_usuario" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?')">Eliminar</button>
                                    </form>
                                    <form action="lista-usuarios.php?pagina=<?= $paginaActual ?>&busqueda=<?= htmlspecialchars($busqueda) ?>" method="post" style="display:inline-block;">
                                        <input type="hidden" name="id_usuario" value="<?= $row['Id_usuario'] ?>">
                                        <input type="hidden" name="estado" value="<?= $row['estado'] ?>">
                                        <button class="table-button" type="submit" name="cambiar_estado">Cambiar Estado</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <?php if ($paginaActual > 1) : ?>
                        <a href="lista-usuarios.php?pagina=<?= $paginaActual - 1 ?>&busqueda=<?= htmlspecialchars($busqueda) ?>">&laquo; Anterior</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                        <a class="<?= $i == $paginaActual ? 'active' : '' ?>" href="lista-usuarios.php?pagina=<?= $i ?>&busqueda=<?= htmlspecialchars($busqueda) ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($paginaActual < $totalPaginas) : ?>
                        <a href="lista-usuarios.php?pagina=<?= $paginaActual + 1 ?>&busqueda=<?= htmlspecialchars($busqueda) ?>">Siguiente &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php else : ?>
                <p>No se encontraron usuarios.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$stmtTotal->close();
$conex->close();
?>