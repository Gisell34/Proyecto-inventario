<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$sql = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto";
$resultado = $conex->query($sql);

if ($resultado === false) {
    die("Error en la consulta SQL: " . $conex->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Lista de Productos</h1>
    <div class="user-panel">
        <a href="user.php">Regresar a Panel de usuario</a>
    </div>
    <div class="list-container">
        <?php
        if ($resultado->num_rows > 0) {
            echo "<table class='user-table'>
                    <thead>
                        <tr>
                            <th>Código de Producto</th>
                            <th>Nombre del Producto</th>
                            <th>Fabricante</th>
                            <th>Tipo de Producto</th>
                            <th>Especificaciones</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $resultado->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['Codigoproducto']}</td>
                        <td>{$row['Nombre_producto']}</td>
                        <td>{$row['Fabricante']}</td>
                        <td>{$row['Tipo_producto']}</td>
                        <td>{$row['Especificaciones']}</td>
                        <td>
                            <form method='POST' action='agregar_producto_carrito.php'>
                                <input type='hidden' name='codigo' value='{$row['Codigoproducto']}'>
                                <button type='submit'>Agregar al Carrito</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "No se encontraron productos.";
        }
        ?>
    </div>
</body>
</html>