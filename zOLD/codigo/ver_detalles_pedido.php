<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
include('conexion.php');

$id_pedido = intval($_GET['id_pedido']);

// Consultar los detalles del pedido
$sql_detalle_pedido = "SELECT d.id_pedido, p.Nombre_producto, d.cantidad, d.precio_unitario, (d.cantidad * d.precio_unitario) as subtotal
                       FROM detalle_pedidos d
                       JOIN producto p ON d.id_producto = p.Codigoproducto
                       WHERE d.id_pedido = ?";
$stmt = $conex->prepare($sql_detalle_pedido);
$stmt->bind_param("i", $id_pedido);
$stmt->execute();
$resultado_detalle_pedido = $stmt->get_result();

// Consultar información del pedido
$sql_pedido_info = "SELECT p.id_pedido, p.fecha_pedido, u.Usuario, SUM(d.cantidad * d.precio_unitario) as total
                    FROM pedidos p
                    JOIN usuarios u ON p.id_usuario = u.Id_usuario
                    JOIN detalle_pedidos d ON p.id_pedido = d.id_pedido
                    WHERE p.id_pedido = ?";
$stmt_info = $conex->prepare($sql_pedido_info);
$stmt_info->bind_param("i", $id_pedido);
$stmt_info->execute();
$resultado_pedido_info = $stmt_info->get_result()->fetch_assoc();

if ($resultado_detalle_pedido === false || $resultado_pedido_info === false) {
    die("Error en la consulta SQL: " . $conex->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Detalles del Pedido</title>
    <style>
        /* (Aquí va tu estilo CSS para los detalles del pedido) */
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="admin.php">Regresar al panel de administración</a>
        </nav>
    </header>

    <div class="order-details">
        <h1>Detalles del Pedido</h1>
        <h2>ID Pedido: <?= $resultado_pedido_info['id_pedido'] ?></h2>
        <p>Usuario: <?= $resultado_pedido_info['Usuario'] ?></p>
        <p>Fecha: <?= $resultado_pedido_info['fecha_pedido'] ?></p>
        <p>Total: <?= $resultado_pedido_info['total'] ?></p>

        <h3>Productos en el Pedido</h3>
        <?php if ($resultado_detalle_pedido->num_rows > 0) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Nombre Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado_detalle_pedido->fetch_assoc()) : ?>
                        <tr>
                            <td><?= $row["Nombre_producto"] ?></td>
                            <td><?= $row["cantidad"] ?></td>
                            <td><?= $row["precio_unitario"] ?></td>
                            <td><?= $row["subtotal"] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No hay productos en este pedido.</p>
        <?php endif; ?>
    </div>
</body>
</html>