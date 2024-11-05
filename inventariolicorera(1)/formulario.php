<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Producto</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Agregar Producto</h1>
    <form action="guardar_producto.php" method="POST">
        <label for="codigo">Código del Producto:</label>
        <input type="text" name="codigo" id="codigo" required><br>

        <label for="tipo">Tipo de Producto:</label>
        <input type="text" name="tipo" id="tipo" required><br>

        <label for="nombre">Nombre del Producto:</label>
        <input type="text" name="nombre" id="nombre" required><br>

        <label for="fabricante">Fabricante:</label>
        <input type="text" name="fabricante" id="fabricante" required><br>

        <label for="especificaciones">Especificaciones:</label>
        <textarea name="especificaciones" id="especificaciones" required></textarea><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" name="cantidad" id="cantidad" required><br>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" required><br>

        <input type="submit" name="accion" value="Guardar Producto">
        <input type="submit" name="accion" value="Guardar y Agregar Otro">
    </form>
    <br>
    <a href="admin.php">Volver al Panel de Administración</a>
</body>
</html>