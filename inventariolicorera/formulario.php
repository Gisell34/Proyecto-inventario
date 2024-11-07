<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro de Productos</title>
    <link rel="stylesheet" href="styles.css"> <!-- Enlace a la hoja de estilos -->
</head>
<body>
    <h2>Registro de Producto</h2>
    <form action="guardar_producto.php" method="POST" class="form-styles">

        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="fabricante">Fabricante:</label>
        <input type="text" id="fabricante" name="fabricante" required><br><br>

        <label for="tipo">Tipo de Producto:</label>
        <input type="text" id="tipo" name="tipo" required><br><br>

        <label for="especificaciones">Especificaciones:</label><br>
        <textarea id="especificaciones" name="especificaciones" rows="4" required></textarea><br><br>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" required><br><br>

        <label for="precio">Precio:</label>
        <input type="number" id="precio" name="precio" step="0.01" required><br><br>

        <input type="submit" value="Guardar Producto">
        <input type="submit" name="accion" value="Guardar y Agregar Otro">
    </form>
    <br>
    <a href="admin.php">Volver al Panel de Administración</a>
</body>
</html>