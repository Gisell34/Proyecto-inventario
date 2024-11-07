<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
include('conexion.php');

$sql_usuarios = "SELECT Id_usuario, Usuario, rol FROM usuarios";
$resultado_usuarios = $conex->query($sql_usuarios);

$sql_proveedores = "SELECT Codigoproveedor, Nombre_proveedor, Telefono, Nit, Direccion, Correo_electronico FROM proveedor";
$resultado_proveedores = $conex->query($sql_proveedores);

$sql_servicios = "SELECT Codigo, Nombre_servicio, Tipo_servicio FROM servicios";
$resultado_servicios = $conex->query($sql_servicios);

$sql_productos_inventario = "SELECT p.Codigoproducto, p.Nombre_producto, p.Fabricante, p.Tipo_producto, p.Especificaciones,
                                    i.Cantidad_producto, i.Precio, i.Entrada, i.Salida, i.stock_minimo, i.stock_maximo
                            FROM producto p
                            LEFT JOIN inventario i ON p.Codigoproducto = i.Codigoproducto";
$resultado_productos_inventario = $conex->query($sql_productos_inventario);

if ($resultado_productos_inventario === false) {
    die("Error en la consulta SQL de productos: " . $conex->error);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Admin - Panel de Administración</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        .button {
            margin-bottom: 10px;
        }
    </style>
    <script>
        function mostrarUsuarios() {
            ocultarSecciones();
            document.getElementById("usuarios").style.display = "block";
        }

        function mostrarProveedores() {
            ocultarSecciones();
            document.getElementById("proveedores").style.display = "block";
        }

        function mostrarServicios() {
            ocultarSecciones();
            document.getElementById("servicios").style.display = "block";
        }

        function mostrarProductos() {
            ocultarSecciones();
            document.getElementById("productos").style.display = "block";
        }

        function ocultarSecciones() {
            document.getElementById("usuarios").style.display = "none";
            document.getElementById("proveedores").style.display = "none";
            document.getElementById("servicios").style.display = "none";
            document.getElementById("productos").style.display = "none";
        }
    </script>
</head>

<body>
    <h1>Bienvenido al panel de administración</h1>
    <div class="container">
        <div class="sidebar">
            <button class="button" onclick="mostrarUsuarios()">Lista de Usuarios</button>
            <button class="button" onclick="mostrarProveedores()">Lista de Proveedores</button>
            <button class="button" onclick="mostrarServicios()">Lista de Servicios</button>
            <button class="button" onclick="mostrarProductos()">Lista de Productos</button>
            <a href="logout.php" class="button">Cerrar Sesión</a>
        </div>
        <div class="content">
            <div class="list-container">

                <!-- Sección de Lista de Usuarios -->
                <div id="usuarios" style="display: none;">
                    <h2>Lista de Usuarios</h2>
                    <a href="#" onclick="ocultarSecciones();">Regresar al Panel de Administración</a>
                    <?php if ($resultado_usuarios->num_rows > 0) : ?>
                        <table>
                            <tr>
                                <th>ID</th>
                                <th>Usuario</th>
                                <th>Rol</th>
                            </tr>
                            <?php while ($row = $resultado_usuarios->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $row["Id_usuario"] ?></td>
                                    <td><?= $row["Usuario"] ?></td>
                                    <td><?= $row["rol"] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else : ?>
                        <p>No se encontraron usuarios.</p>
                    <?php endif; ?>
                </div>
                <!-- Fin de Sección de Lista de Usuarios -->

                <!-- Sección de Lista de Proveedores -->
                <div id="proveedores" style="display: none;">
                    <h2>Lista de Proveedores</h2>
                    <a href="#" onclick="ocultarSecciones();">Regresar al Panel de Administración</a>
                    <a href="formulario_proveedores.html" class="button">Agregar Proveedor</a>
                    <?php if ($resultado_proveedores->num_rows > 0) : ?>
                        <table>
                            <tr>
                                <th>Código del Proveedor</th>
                                <th>Nombre del Proveedor</th>
                                <th>Teléfono</th>
                                <th>NIT</th>
                                <th>Dirección</th>
                                <th>Correo Electrónico</th>
                                <th>Acciones</th>
                            </tr>
                            <?php while ($row = $resultado_proveedores->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $row["Codigoproveedor"] ?></td>
                                    <td><?= $row["Nombre_proveedor"] ?></td>
                                    <td><?= $row["Telefono"] ?></td>
                                    <td><?= $row["Nit"] ?></td>
                                    <td><?= $row["Direccion"] ?></td>
                                    <td><?= $row["Correo_electronico"] ?></td>
                                    <td>
                                        <a href="modificar_proveedor.php?codigo=<?= $row['Codigoproveedor'] ?>">Modificar</a> |
                                        <a href="eliminar_proveedor.php?codigo=<?= $row['Codigoproveedor'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else : ?>
                        <p>No se encontraron proveedores.</p>
                    <?php endif; ?>
                </div>
                <!-- Fin de Sección de Lista de Proveedores -->

                <!-- Sección de Lista de Servicios -->
                <div id="servicios" style="display: none;">
                    <h2>Lista de Servicios</h2>
                    <a href="#" onclick="ocultarSecciones();">Regresar al Panel de Administración</a>
                    <a href="formulario_servicios.html" class="button">Agregar Servicio</a>
                    <?php if ($resultado_servicios->num_rows > 0) : ?>
                        <table>
                            <tr>
                                <th>Código</th>
                                <th>Nombre del Servicio</th>
                                <th>Tipo de Servicio</th>
                                <th>Acciones</th>
                            </tr>
                            <?php while ($row = $resultado_servicios->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $row["Codigo"] ?></td>
                                    <td><?= $row["Nombre_servicio"] ?></td>
                                    <td><?= $row["Tipo_servicio"] ?></td>
                                    <td>
                                        <a href="activar_servicio.php?codigo=<?= $row['Codigo'] ?>">Activar</a> |
                                        <a href="inactivar_servicio.php?codigo=<?= $row['Codigo'] ?>">Inactivar</a> |
                                        <a href="eliminar_servicio.php?codigo=<?= $row['Codigo'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else : ?>
                        <p>No se encontraron servicios.</p>
                    <?php endif; ?>
                </div>
                <!-- Fin de Sección de Lista de Servicios -->

                <!-- Sección de Lista de Productos -->
                <div id="productos" style="display: none;">
                    <h2>Lista de Productos</h2>
                    <a href="#" onclick="ocultarSecciones();">Regresar al Panel de Administración</a>
                    <a href="formulario.php" class="button">Agregar Producto</a>
                    <?php if ($resultado_productos_inventario->num_rows > 0) : ?>
                        <table>
                            <tr>
                                <th>Código de Producto</th>
                                <th>Nombre del Producto</th>
                                <th>Fabricante</th>
                                <th>Tipo de Producto</th>
                                <th>Especificaciones</th>
                                <th>Cantidad de Producto</th>
                                <th>Precio</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Stock Mínimo</th>
                                <th>Stock Máximo</th>
                                <th>Acciones</th>
                            </tr>
                            <?php while ($row = $resultado_productos_inventario->fetch_assoc()) : ?>
                                <tr>
                                    <td><?= $row["Codigoproducto"] ?></td>
                                    <td><?= $row["Nombre_producto"] ?></td>
                                    <td><?= $row["Fabricante"] ?></td>
                                    <td><?= $row["Tipo_producto"] ?></td>
                                    <td><?= $row["Especificaciones"] ?></td>
                                    <td><?= $row["Cantidad_producto"] ?></td>
                                    <td><?= $row["Precio"] ?></td>
                                    <td><?= $row["Entrada"] ?></td>
                                    <td><?= $row["Salida"] ?></td>
                                    <td><?= $row["stock_minimo"] ?></td>
                                    <td><?= $row["stock_maximo"] ?></td>
                                    <td>
                                        <a href="modificar_producto.php?codigo=<?= $row['Codigoproducto'] ?>">Modificar</a> |
                                        <a href="eliminar_producto.php?codigo=<?= $row['Codigoproducto'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php else : ?>
                        <p>No se encontraron productos.</p>
                    <?php endif; ?>
                </div>
                <!-- Fin de Sección de Lista de Productos -->

            </div>
        </div>
    </div>
</body>

</html>

<?php
$conex->close();
?>