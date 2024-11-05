<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('database.php');

// Función para obtener una factura por ID
function obtenerFacturaPorId($conn, $id) {
    $sql = "SELECT * FROM facturas WHERE ID_Factura=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Función para actualizar una factura
function actualizarFactura($conn, $id, $fecha, $productos, $cantidad, $precio, $nombre_servicio, $tipo_servicio, $precio_venta) {
    // Calcular el total
    $total = $cantidad * $precio;

    $sql = "UPDATE facturas SET Fecha=?, Productos=?, Cantidad=?, Precio=?, Nombre_servicio=?, Tipo_servicio=?, Precio_venta=?, Total=? WHERE ID_Factura=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissdii", $fecha, $productos, $cantidad, $precio, $nombre_servicio, $tipo_servicio, $precio_venta, $total, $id);
    $stmt->execute();
    $stmt->close();
}

// Función para agregar productos al inventario
function agregarProductoInventario($conn, $codigo_producto, $cantidad) {
    // Obtener los datos actuales del producto
    $sql = "SELECT * FROM inventario WHERE Codigoproducto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo_producto);
    $stmt->execute();
    $result = $stmt->get_result();
    $producto = $result->fetch_assoc();

    if ($producto) {
        // Actualizar las entradas y salidas
        $nuevas_entradas = $producto['Entradas'] + $cantidad;
        $nuevas_salidas = $producto['Salidas'] - $cantidad;

        $sql = "UPDATE inventario SET Entradas=?, Salidas=? WHERE Codigoproducto=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $nuevas_entradas, $nuevas_salidas, $codigo_producto);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "El producto con código $codigo_producto no existe en el inventario.";
    }
}

// Función para eliminar un producto de la factura y actualizar el inventario
function eliminarProductoDeFactura($conn, $id_factura, $codigo_producto, $cantidad) {
    // Obtener la factura y los productos
    $factura = obtenerFacturaPorId($conn, $id_factura);
    if ($factura) {
        // Convertir la lista de productos a un array
        $productos = explode(",", $factura['Productos']);
        $nueva_lista_productos = array_filter($productos, function($producto) use ($codigo_producto) {
            return $producto != $codigo_producto;
        });

        // Actualizar la lista de productos en la factura
        $nueva_lista_productos_str = implode(",", $nueva_lista_productos);
        $sql = "UPDATE facturas SET Productos=? WHERE ID_Factura=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $nueva_lista_productos_str, $id_factura);
        $stmt->execute();
        $stmt->close();

        // Actualizar el inventario
        agregarProductoInventario($conn, $codigo_producto, $cantidad);
    }
}

// Manejo de formularios
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['actualizar_factura'])) {
        $id_factura = intval($_POST['id_factura']);
        $fecha = $_POST['fecha'];
        $productos = $_POST['productos'];  // Lista de productos
        $cantidad = intval($_POST['cantidad']);  // Cantidad total de productos
        $precio = floatval($_POST['precio']);  // Precio total de los productos
        $nombre_servicio = $_POST['nombre_servicio'];
        $tipo_servicio = $_POST['tipo_servicio'];
        $precio_venta = floatval($_POST['precio_venta']);
        actualizarFactura($conn, $id_factura, $fecha, $productos, $cantidad, $precio, $nombre_servicio, $tipo_servicio, $precio_venta);
    }

    if (isset($_POST['agregar_inventario'])) {
        $codigo_producto = intval($_POST['codigo_producto']);
        $cantidad = intval($_POST['cantidad_producto']);
        agregarProductoInventario($conn, $codigo_producto, $cantidad);
    }

    if (isset($_POST['eliminar_producto'])) {
        $id_factura = intval($_POST['id_factura']);
        $codigo_producto = intval($_POST['codigo_producto']);
        $cantidad = intval($_POST['cantidad_producto']);
        eliminarProductoDeFactura($conn, $id_factura, $codigo_producto, $cantidad);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Factura</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        header nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #9495b9;
        }

        form {
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 5px;
        }

        form input, form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #7bc0c5;
            border-radius: 5px;
        }

        form button {
            background-color: #7bc0c5;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="admin.php">Volver al panel del Administrador</a>
        </nav>
    </header>
    <div class="container">
        <h1>Editar Factura</h1>

        <!-- Buscar Factura -->
        <form method="post" action="">
            <label for="id_factura">ID de Factura:</label>
            <input type="text" id="id_factura" name="id_factura" required>
            <button type="submit" name="buscar_factura">Buscar Factura</button>
        </form>

        <!-- Editar Factura -->
        <?php if (isset($_POST['buscar_factura'])): ?>
            <?php
            $id_factura = intval($_POST['id_factura']);
            $factura = obtenerFacturaPorId($conn, $id_factura);
            ?>

            <?php if ($factura): ?>
                <h2>Actualizar Factura</h2>
                <form method="post" action="">
                    <input type="hidden" name="id_factura" value="<?= htmlspecialchars($factura['ID_Factura']) ?>">
                    <label for="fecha">Fecha:</label>
                    <input type="date" id="fecha" name="fecha" value="<?= htmlspecialchars($factura['Fecha'] ?? '') ?>" required>
                    <label for="productos">Productos:</label>
                    <input type="text" id="productos" name="productos" value="<?= htmlspecialchars($factura['Productos'] ?? '') ?>" required>
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" value="<?= htmlspecialchars($factura['Cantidad'] ?? '') ?>" required>
                    <label for="precio">Precio:</label>
                    <input type="text" id="precio" name="precio" value="<?= htmlspecialchars($factura['Precio'] ?? '') ?>" required>
                    <label for="nombre_servicio">Nombre del Servicio:</label>
                    <input type="text" id="nombre_servicio" name="nombre_servicio" value="<?= htmlspecialchars($factura['Nombre_servicio'] ?? '') ?>">
                    <label for="tipo_servicio">Tipo de Servicio:</label>
                    <input type="text" id="tipo_servicio" name="tipo_servicio" value="<?= htmlspecialchars($factura['Tipo_servicio'] ?? '') ?>">
                    <label for="precio_venta">Precio de Venta:</label>
                    <input type="text" id="precio_venta" name="precio_venta" value="<?= htmlspecialchars($factura['Precio_venta'] ?? '') ?>">
                    <button type="submit" name="actualizar_factura">Actualizar Factura</button>
                </form>
            <?php else: ?>
                <div class="message error">No se encontró la factura con ID <?= htmlspecialchars($id_factura) ?>.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Agregar Producto al Inventario -->
        <h2>Agregar Producto al Inventario</h2>
        <form method="post" action="">
            <label for="codigo_producto">Código del Producto:</label>
            <input type="text" id="codigo_producto" name="codigo_producto" required>
            <label for="cantidad_producto">Cantidad:</label>
            <input type="number" id="cantidad_producto" name="cantidad_producto" required>
            <button type="submit" name="agregar_inventario">Agregar al Inventario</button>
        </form>

        <!-- Eliminar Producto de la Factura -->
        <h2>Eliminar Producto de la Factura</h2>
        <form method="post" action="">
            <label for="id_factura">ID de Factura:</label>
            <input type="text" id="id_factura" name="id_factura" required>
            <label for="codigo_producto">Código del Producto:</label>
            <input type="text" id="codigo_producto" name="codigo_producto" required>
            <label for="cantidad_producto">Cantidad:</label>
            <input type="number" id="cantidad_producto" name="cantidad_producto" required>
            <button type="submit" name="eliminar_producto">Eliminar Producto</button>
        </form>
    </div>
</body>

</html>