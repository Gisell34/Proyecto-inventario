<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
include('conexion.php');

// Consultas para obtener los datos
$sql_productos = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto";
$resultado_productos = $conex->query($sql_productos);

$sql_usuarios = "SELECT Id_usuario, Usuario, rol FROM usuarios";
$resultado_usuarios = $conex->query($sql_usuarios);

$sql_proveedores = "SELECT Codigoproveedor, Nombre_proveedor, Telefono, Nit, Direccion, Correo_electronico FROM proveedor";
$resultado_proveedores = $conex->query($sql_proveedores);

$sql_inventario = "SELECT Codigoproducto, Cantidad_producto, Precio, Entrada, Salida, stock_minimo, stock_maximo FROM inventario";
$resultado_inventario = $conex->query($sql_inventario);

$sql_servicios = "SELECT Codigo, Nombre_servicio, Tipo_servicio FROM servicios";
$resultado_servicios = $conex->query($sql_servicios);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Panel de Administración</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Bienvenido al panel de administración</h1>
    <div class="container">
        <div class="sidebar">
            <a href="formulario.php" class="button">Agregar Producto</a>
            <a href="#productos" class="button">Lista de Productos</a>
            <a href="#usuarios" class="button">Lista de Usuarios</a>
            <a href="#proveedores" class="button">Lista de Proveedores</a>
            <a href="#inventario" class="button">Inventario</a>
            <a href="#servicios" class="button">Lista de Servicios</a>
            <a href="logout.php" class="button">Cerrar Sesión</a>
        </div>
        <div class="content">
            <div class="list-container">
                <h2 id="productos">Lista de Productos</h2>
                <?php if ($resultado_productos->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Código de Producto</th>
                            <th>Nombre del Producto</th>
                            <th>Fabricante</th>
                            <th>Tipo de Producto</th>
                            <th>Especificaciones</th>
                        </tr>
                        <?php while ($row = $resultado_productos->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row["Codigoproducto"] ?></td>
                                <td><?= $row["Nombre_producto"] ?></td>
                                <td><?= $row["Fabricante"] ?></td>
                                <td><?= $row["Tipo_producto"] ?></td>
                                <td><?= $row["Especificaciones"] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No se encontraron productos.</p>
                <?php endif; ?>

                <h2 id="usuarios">Lista de Usuarios</h2>
                <?php if ($resultado_usuarios->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Rol</th>
                        </tr>
                        <?php while ($row = $resultado_usuarios->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row["Id_usuario"] ?></td>
                                <td><?= $row["Usuario"] ?></td>
                                <td><?= $row["rol"] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No se encontraron usuarios.</p>
                <?php endif; ?>

                <h2 id="proveedores">Lista de Proveedores</h2>
                <?php if ($resultado_proveedores->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Código del Proveedor</th>
                            <th>Nombre del Proveedor</th>
                            <th>Teléfono</th>
                            <th>NIT</th>
                            <th>Dirección</th>
                            <th>Correo Electrónico</th>
                        </tr>
                        <?php while ($row = $resultado_proveedores->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row["Codigoproveedor"] ?></td>
                                <td><?= $row["Nombre_proveedor"] ?></td>
                                <td><?= $row["Telefono"] ?></td>
                                <td><?= $row["Nit"] ?></td>
                                <td><?= $row["Direccion"] ?></td>
                                <td><?= $row["Correo_electronico"] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No se encontraron proveedores.</p>
                <?php endif; ?>

                <h2 id="inventario">Inventario</h2>
                <?php if ($resultado_inventario->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Código de Producto</th>
                            <th>Cantidad de Producto</th>
                            <th>Precio</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Stock Mínimo</th>
                            <th>Stock Máximo</th>
                        </tr>
                        <?php while ($row = $resultado_inventario->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row["Codigoproducto"] ?></td>
                                <td><?= $row["Cantidad_producto"] ?></td>
                                <td><?= $row["Precio"] ?></td>
                                <td><?= $row["Entrada"] ?></td>
                                <td><?= $row["Salida"] ?></td>
                                <td><?= $row["stock_minimo"] ?></td>
                                <td><?= $row["stock_maximo"] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No se encontraron datos en el inventario.</p>
                <?php endif; ?>

                <h2 id="servicios">Lista de Servicios</h2>
                <?php if ($resultado_servicios->num_rows > 0): ?>
                    <table>
                        <tr>
                            <th>Código</th>
                            <th>Nombre del Servicio</th>
                            <th>Tipo de Servicio</th>
                        </tr>
                        <?php while ($row = $resultado_servicios->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row["Codigo"] ?></td>
                                <td><?= $row["Nombre_servicio"] ?></td>
                                <td><?= $row["Tipo_servicio"] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </table>
                <?php else: ?>
                    <p>No se encontraron servicios.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>