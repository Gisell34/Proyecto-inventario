<?php

session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
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
    <title>Usuario - Ver Productos</title>

    <style>
        table {
            width: 50%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>

</head>

<body>
    <h1>Bienvenido al panel de usuario</h1>
    <h2>Lista de Productos</h2>
    <link rel="stylesheet" type="text/css" href="styles.css">

    <?php

    if ($resultado->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Código de Producto</th>
                    <th>Nombre del Producto</th>
                    <th>Fabricante</th>
                    <th>Tipo de Producto</th>
                    <th>Especificaciones</th>
                </tr>";
        while ($row = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["Codigoproducto"] . "</td>
                    <td>" . $row["Nombre_producto"] . "</td>
                    <td>" . $row["Fabricante"] . "</td>
                    <td>" . $row["Tipo_producto"] . "</td>
                    <td>" . $row["Especificaciones"] . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron productos.";
    }
    ?>

    <br>
    <a href="carrito.php">Ver Carrito de Compras</a><br>
    <a href="logout.php">Cerrar Sesión</a>

</body>

</html>