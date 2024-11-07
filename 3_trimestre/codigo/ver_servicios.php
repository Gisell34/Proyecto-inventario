<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: index.html");
    exit();
}

include('conexion.php');

$sql = "SELECT Codigo, Nombre_servicio, Tipo_servicio FROM servicios";
$resultado = $conex->query($sql);

if ($resultado === false) {
    die("Error en la consulta SQL: " . $conex->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Servicios</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Lista de Servicios</h1>
    <div class="user-panel">
        <a href="user.php">Regresar a panel de usuario</a>
    </div>
    <div class="list-container">
        <?php
        if ($resultado->num_rows > 0) {
            echo "<table class='user-table'>
                    <thead>
                        <tr>
                            <th>Código de Servicio</th>
                            <th>Nombre del Servicio</th>
                            <th>Tipo de Servicio</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Codigo']}</td>
                        <td>{$row['Nombre_servicio']}</td>
                        <td>{$row['Tipo_servicio']}</td>
                        <td>
                            <form method='POST' action='agregar_servicio_carrito.php'>
                                <input type='hidden' name='codigo' value='{$row['Codigo']}'>
                                <button type='submit'>Solicitar Servicio</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "No se encontraron servicios.";
        }
        ?>
    </div>
</body>
</html>