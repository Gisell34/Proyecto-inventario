<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
include('conexion.php');

// Consulta para obtener los productos
$sql_producto = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto";
$resultado_producto = $conex->query($sql_producto);

// Consulta para obtener los usuarios
$sql_usuarios = "SELECT Id_usuario, Usuario, rol FROM usuarios";
$resultado_usuarios = $conex->query($sql_usuarios);

// Consulta para obtener los proveedores
$sql_proveedor = "SELECT Codigoproveedor, nombre_proveedor, Telefono FROM proveedor";
$resultado_proveedor = $conex->query($sql_proveedor);

// Consulta para obtener el inventario
$sql_inventario = "SELECT Codigoproducto, Cantidad_producto, Precio FROM inventario";
$resultado_inventario = $conex->query($sql_inventario);

// Consulta para obtener los servicios
$sql_tipo_servicio = "SELECT Codigo, nombre_servicio, Tipo_servicio FROM tipo_servicio";
$resultado_tipo_servicio = $conex->query($sql_tipo_servicio);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Panel de Administración</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Bienvenido al panel de administración</h1>
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
        <h2 id="productos">Lista de Productos</h2>
        <?php
        if ($resultado_producto->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Código de Producto</th>
                        <th>Nombre del Producto</th>
                        <th>Fabricante</th>
                        <th>Tipo de Producto</th>
                        <th>Especificaciones</th>
                    </tr>";
            while ($row = $resultado_producto->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Codigoproducto"] . "</td>
                        <td>" . $row["Nombre_producto"] . "</td>
                        <td>" . $row["Fabricante"] . "</td>
                        <td>" . $row["Tipo_producto"] . "</td>
                        <td>" . $row["Especificaciones"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron productos.";
        }
        ?>

        <h2 id="usuarios">Lista de Usuarios</h2>
        <?php
        if ($resultado_usuarios->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                    </tr>";
            while ($row = $resultado_usuarios->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Id_usuario"] . "</td>
                        <td>" . $row["Usuario"] . "</td>
                        <td>" . $row["rol"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron usuarios.";
        }
        ?>

        <h2 id="proveedores">Lista de Proveedor</h2>
        <?php
        if ($resultado_proveedor->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Contacto</th>
                    </tr>";
            while ($row = $resultado_proveedores->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["nombre"] . "</td>
                        <td>" . $row["contacto"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron proveedores.";
        }
        ?>

        <h2 id="inventario">Inventario</h2>
        <?php
        if ($resultado_inventario->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Código de Producto</th>
                        <th>Nombre del Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>";
            while ($row = $resultado_inventario->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["Codigoproducto"] . "</td>
                        <td>" . $row["Nombre_producto"] . "</td>
                        <td>" . $row["Cantidad"] . "</td>
                        <td>" . $row["Precio"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron datos en el inventario.";
        }
        ?>

        <h2 id="servicios">Lista de Servicios</h2>
        <?php
        if ($resultado_tipo_servicio->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Nombre del Servicio</th>
                        <th>Descripción</th>
                        <th>Costo</th>
                    </tr>";
            while ($row = $resultado_tipo_servicio->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row["id"] . "</td>
                        <td>" . $row["nombre_servicio"] . "</td>
                        <td>" . $row["descripcion"] . "</td>
                        <td>" . $row["costo"] . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No se encontraron servicios.";
        }
        ?>
    </div>
</body>

</html>