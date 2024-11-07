<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include('conexion.php');

// Consulta para obtener los productos
$sql = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto";
$resultado = $conex->query($sql);

?>

<!DOCTYPE html>

<html>

<head>
    <title>Ver Productos</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <h1>Lista de Productos</h1>
    <?php
    if ($resultado->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>Código de Producto</th>
                    <th>Tipo de Producto</th>
                    <th>Nombre del Producto</th>
                    <th>Fabricante</th>
                    <th>Especificaciones</th>
                </tr>";

        // Output de cada fila
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["Codigoproducto"] . "</td>
                    <td>" . $row["Tipo_producto"] . "</td>
                    <td>" . $row["Nombre_producto"] . "</td>
                    <td>" . $row["Fabricante"] . "</td>
                    <td>" . $row["Especificaciones"] . "</td>
                  </tr>";
        }

        echo "</table>";

    } else {
        echo "No se encontraron productos.";
    }
    ?>
    <br>

    <a href="agregar_producto.php">Agregar Nuevo Producto</a><br>
    <a href="admin.php">Volver al Panel de Administración</a><br>
    <a href="user.php">Volver al Panel de Usuario</a><br>
    <a href="logout.php">Cerrar Sesión</a>

</body>

</html>