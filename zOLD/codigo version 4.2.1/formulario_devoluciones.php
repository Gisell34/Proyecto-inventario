<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
include('conexion.php');

// Función para obtener las facturas
function obtenerFacturas($conn)
{
    $sql = "SELECT id_factura, fecha, total FROM facturas";
    $result = $conn->query($sql);
    return $result;
}

// Función para eliminar una factura
function eliminarFactura($conn, $id)
{
    $sql = "DELETE FROM facturas WHERE id_factura=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Función para agregar producto al inventario
function agregarProductoInventario($conn, $codigo, $cantidad)
{
    // Obtener el producto actual en inventario
    $sql = "SELECT cantidad FROM inventario WHERE codigoproducto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {

        // Insertar nuevo producto en inventario
        $sql = "INSERT INTO inventario (codigoproducto, cantidad) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $codigo, $cantidad);
        $stmt->execute();
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['eliminar_factura'])) {
        $id_factura = intval($_POST['id_factura']);
        eliminarFactura($conex, $id_factura);
    } elseif (isset($_POST['agregar_producto'])) {
        $codigo_producto = $_POST['codigo_producto'];
        $cantidad = intval($_POST['cantidad']);
        agregarProductoInventario($conex, $codigo_producto, $cantidad);
    }
}

$resultado_facturas = obtenerFacturas($conex);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Devoluciones</title>
    <style>
        /* Añadir estilos específicos para la sección de devoluciones si es necesario */
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 10px 0;
            text-align: center;
            color: white;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .user-panel {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-panel h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-panel a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .user-panel a:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }

        .perfil-usuario {
            display: none;
            /* Oculta la sección de perfil de usuario */
        }

        .user-section {
            margin-top: 20px;
        }

        .list-container {
            background: white;
            padding: 0px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-left: 0%;
            /* Agregar margen izquierdo */
            margin-right: 0%;
            /* Agregar margen derecho */
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th,
        .user-table td {
            padding: 10px;
            text-align: center;
            border: 3px double #7bc0c5;
            vertical-align: middle;
            /* Alinea verticalmente el contenido */
        }

        .user-table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
        }

        .user-table td {
            vertical-align: middle;
            /* Alinea verticalmente el contenido */
        }

        .user-table td form {
            display: inline;
        }

        .user-table td a {
            display: inline-block;
            padding: 5px 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
            /* Ajusta el margen entre botones si es necesario */
        }

        .user-table td a:last-child {
            margin-right: 0;
            /* Elimina el margen derecho del último botón */
        }

        .user-table td a:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }

        .button {
            margin-bottom: 10px;
        }

        .product-list {
            list-style: none;
            padding: 0;
        }

        .product-item {
            background: #f9f9f9;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .product-item h3 {
            margin: 0 0 10px;
        }

        .product-item p {
            margin: 5px 0;
        }

        .product-item .buttons {
            margin-top: 10px;
        }

        .product-item .buttons a {
            margin-right: 5px;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .search-container input {
            flex: 1;
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .search-container button {
            padding: 8px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-container button:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }
    </style>
    </stylbody>
</head>

<body>
    <header>
        <nav>
            <a href="admin.php">Inicio</a>
            <a href="#" onclick="mostrarDevoluciones()">Devoluciones</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="user-panel">
        <h1>Formulario de Devoluciones</h1>
        <div id="devoluciones" style="display: block;">
            <h2>Buscar y Eliminar Facturas</h2>
            <form method="post" action="">
                <label for="id_factura">ID de Factura a eliminar:</label>
                <input type="text" id="id_factura" name="id_factura" required>
                <button type="submit" name="eliminar_factura">Eliminar Factura</button>
            </form>

            <h2>Agregar Producto al Inventario</h2>
            <form method="post" action="">
                <label for="codigo_producto">Código de Producto:</label>
                <input type="text" id="codigo_producto" name="codigo_producto" required>
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" required>
                <button type="submit" name="agregar_producto">Agregar al Inventario</button>
            </form>

            <h2>Facturas Anteriores</h2>
            <?php if ($resultado_facturas->num_rows > 0) : ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $resultado_facturas->fetch_assoc()) : ?>
                            <tr>
                                <td><?= htmlspecialchars($row["id_factura"]) ?></td>
                                <td><?= htmlspecialchars($row["fecha"]) ?></td>
                                <td><?= htmlspecialchars($row["total"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>No se encontraron facturas.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>