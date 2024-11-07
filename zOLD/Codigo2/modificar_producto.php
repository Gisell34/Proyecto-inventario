<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto WHERE Codigoproducto = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "Código de producto no proporcionado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $fabricante = $_POST['fabricante'];
    $tipo = $_POST['tipo'];
    $especificaciones = $_POST['especificaciones'];

    $sql = "UPDATE producto SET Nombre_producto = ?, Fabricante = ?, Tipo_producto = ?, Especificaciones = ? WHERE Codigoproducto = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $fabricante, $tipo, $especificaciones, $codigo);

    if ($stmt->execute()) {
        echo "Producto actualizado exitosamente.";
    } else {
        echo "Error al actualizar el producto: " . $conex->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Producto</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Modificar Producto</h1>
    <form method="POST" action="">
        <label for="nombre">Nombre del Producto:</label>
        <input type="text" id="nombre" name="nombre" value="<?php echo $producto['Nombre_producto']; ?>" required>
        <br>
        <label for="fabricante">Fabricante:</label>
        <input type="text" id="fabricante" name="fabricante" value="<?php echo $producto['Fabricante']; ?>" required>
        <br>
        <label for="tipo">Tipo de Producto:</label>
        <input type="text" id="tipo" name="tipo" value="<?php echo $producto['Tipo_producto']; ?>" required>
        <br>
        <label for="especificaciones">Especificaciones:</label>
        <textarea id="especificaciones" name="especificaciones" required><?php echo $producto['Especificaciones']; ?></textarea>
        <br>
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="admin.php#productos">Volver a la lista de productos</a>
</body>
</html>