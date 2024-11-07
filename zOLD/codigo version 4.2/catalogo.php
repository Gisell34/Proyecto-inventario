<?php
session_start();
include('conexion.php');

// Verificar si la conexión a la base de datos es exitosa
if ($conex->connect_error) {
    die("Error de conexión: " . $conex->connect_error);
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_carrito'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

    // Identificar si es un producto o un servicio y almacenar en el carrito
    if ($tipo === 'producto') {
        $consulta = "SELECT p.Nombre_producto, i.precio_venta 
                     FROM producto p 
                     JOIN inventario i ON p.Codigoproducto = i.Codigoproducto 
                     WHERE p.Codigoproducto = ?";
    } elseif ($tipo === 'servicio') {
        $consulta = "SELECT Nombre_servicio, precio_venta FROM servicios WHERE Codigo = ?";
    }

    if ($stmt = $conex->prepare($consulta)) {
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        $stmt->close();

        if ($row) {
            $nombre = $row['Nombre_producto'] ?? $row['Nombre_servicio'];
            $precio = $row['precio_venta'];

            $carrito[$codigo] = [
                'nombre' => $nombre,
                'cantidad' => $cantidad,
                'precio' => $precio,
                'tipo' => $tipo
            ];

            $_SESSION['carrito'] = $carrito;

            echo "<script>alert('Artículo añadido al carrito.');</script>";
        }
    } else {
        echo "Error en la consulta: " . $conex->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Catálogo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
        }

        .catalogo-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .catalogo-item {
            border-bottom: 1px solid #ccc;
            padding: 15px 0;
        }

        .catalogo-item h3 {
            margin: 0;
        }

        .catalogo-item form {
            display: inline-block;
            margin-top: 10px;
        }

        .catalogo-item select,
        .catalogo-item button {
            padding: 5px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="catalogo-container">
        <h1>Catálogo de Productos y Servicios</h1>

        <!-- Mostrar Productos -->
        <h2>Productos</h2>
        <?php
        $consulta_productos = "SELECT p.Codigoproducto, p.Nombre_producto, p.Tipo_producto, i.precio_venta 
                               FROM producto p 
                               JOIN inventario i ON p.Codigoproducto = i.Codigoproducto";
        $result_productos = $conex->query($consulta_productos);
        while ($row = $result_productos->fetch_assoc()) {
            ?>
            <div class="catalogo-item">
                <h3><?= htmlspecialchars($row['Nombre_producto']) ?></h3>
                <p>Tipo: <?= htmlspecialchars($row['Tipo_producto']) ?></p>
                <p>Precio: $<?= htmlspecialchars($row['precio_venta']) ?></p>
                <form method="POST">
                    <input type="hidden" name="codigo" value="<?= $row['Codigoproducto'] ?>">
                    <input type="hidden" name="tipo" value="producto">
                    <label for="cantidad">Cantidad:</label>
                    <select name="cantidad" required>
                        <?php for ($i = 1; $i <= 100; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" name="agregar_carrito">Agregar al Carrito</button>
                </form>
            </div>
            <?php
        }
        ?>

        <!-- Mostrar Servicios -->
        <h2>Servicios</h2>
        <?php
        $consulta_servicios = "SELECT Codigo, Nombre_servicio, Tipo_servicio FROM servicios";
        $result_servicios = $conex->query($consulta_servicios);
        while ($row = $result_servicios->fetch_assoc()) {
            ?>
            <div class="catalogo-item">
                <h3><?= htmlspecialchars($row['Nombre_servicio']) ?></h3>
                <p>Tipo: <?= htmlspecialchars($row['Tipo_servicio']) ?></p>
                <form method="POST">
                    <input type="hidden" name="codigo" value="<?= $row['Codigo'] ?>">
                    <input type="hidden" name="tipo" value="servicio">
                    <label for="cantidad">Cantidad:</label>
                    <select name="cantidad" required>
                        <?php for ($i = 1; $i <= 100; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" name="agregar_carrito">Agregar al Carrito</button>
                </form>
            </div>
            <?php
        }
        ?>
        <a class="regresar" href="user.php">Regresar</a>
    </div>
</body>
</html>