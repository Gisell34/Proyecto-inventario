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
                                    i.Cantidad_producto, i.Precio, i.precio_venta, i.Entrada, i.Salida, i.stock_minimo, i.stock_maximo
                            FROM producto p
                            LEFT JOIN inventario i ON p.Codigoproducto = i.Codigoproducto";
$resultado_productos_inventario = $conex->query($sql_productos_inventario);

if ($resultado_productos_inventario === false) {
    die("Error en la consulta SQL de productos: " . $conex->error);
}

$sql_compras = "SELECT c.id_compra, c.fecha_compra, cl.Nombres, cl.Apellidos, c.total
                FROM compras c
                JOIN cliente cl ON c.id_usuario = cl.CodigoCliente";
// Consultar compras
$sql_compras = "SELECT c.id_compra, c.fecha_compra, cl.Nombres, cl.Apellidos, c.total
                FROM compras c
                JOIN cliente cl ON c.id_usuario = cl.CodigoCliente";
$resultado_compras = $conex->query($sql_compras);

if ($resultado_compras === false) {
    die("Error en la consulta SQL de compras: " . $conex->error);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Admin - Panel de administración</title>
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

        function mostrarFacturas() {
            ocultarSecciones();
            document.getElementById("facturas").style.display = "block";
        }

        function mostrarCompras() {
            ocultarSecciones();
            document.getElementById("compras").style.display = "block";
        }

        function mostrarDevoluciones() {
            ocultarSecciones();
            document.getElementById("devoluciones").style.display = "block";
        }

        function ocultarSecciones() {
            document.getElementById("usuarios").style.display = "none";
            document.getElementById("proveedores").style.display = "none";
            document.getElementById("servicios").style.display = "none";
            document.getElementById("productos").style.display = "none";
            document.getElementById("facturas").style.display = "none";
            document.getElementById("compras").style.display = "none";
            document.getElementById("devoluciones").style.display = "none";
        }

        function buscarProducto() {
            let input = document.getElementById('buscarProducto').value.toLowerCase();
            let productItems = document.getElementsByClassName('product-item');

            for (let i = 0; i < productItems.length; i++) {
                let nombreProducto = productItems[i].getElementsByTagName('h3')[0].innerText.toLowerCase();
                if (nombreProducto.includes(input)) {
                    productItems[i].style.display = "";
                } else {
                    productItems[i].style.display = "none";
                }
            }
        }
    </script>
</head>

<body>
    <header>
        <nav>
            <a href="#" onclick="mostrarUsuarios()">Usuarios registrados</a>
            <a href="#" onclick="mostrarProveedores()">Proveedores</a>
            <a href="#" onclick="mostrarServicios()">Servicios</a>
            <a href="#" onclick="mostrarProductos()">Inventario</a>
            <a href="#" onclick="mostrarFacturas()">Facturas</a>
            <a href="#" onclick="mostrarCompras()">Compras</a>
            <a href="#" onclick="mostrarDevoluciones()">Devoluciones</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="user-panel">
        <h1>Bienvenido administrador</h1>
        <div class="user-section">
            <!-- Sección de Lista de Usuarios -->
            <div id="usuarios" style="display: none;">
                <h2>Lista de usuarios</h2>
                <a href="#" onclick="ocultarSecciones();">Regresar</a>
                <?php if ($resultado_usuarios->num_rows > 0) : ?>
                    <div class="list-container">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Usuario</th>
                                    <th>Rol</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado_usuarios->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row["Id_usuario"] ?></td>
                                        <td><?= $row["Usuario"] ?></td>
                                        <td><?= $row["rol"] ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p>No se encontraron usuarios.</p>
                <?php endif; ?>
            </div>
            <!-- Fin de Sección de Lista de Usuarios -->

            <!-- Sección de Lista de Proveedores -->
            <div id="proveedores" style="display: none;">
                <h2>Lista de proveedores</h2>
                <a href="#" onclick="ocultarSecciones();">Regresar</a>
                <a href="formulario_proveedores.html" class="button">Agregar proveedor</a>
                <?php if ($resultado_proveedores->num_rows > 0) : ?>
                    <div class="list-container">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>Código del proveedor</th>
                                    <th>Nombre del proveedor</th>
                                    <th>Teléfono</th>
                                    <th>NIT</th>
                                    <th>Dirección</th>
                                    <th>Correo electrónico</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado_proveedores->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row["Codigoproveedor"] ?></td>
                                        <td><?= $row["Nombre_proveedor"] ?></td>
                                        <td><?= $row["Telefono"] ?></td>
                                        <td><?= $row["Nit"] ?></td>
                                        <td><?= $row["Direccion"] ?></td>
                                        <td><?= $row["Correo_electronico"] ?></td>
                                        <td>
                                            <a href="editar_proveedor.php?codigo=<?= $row['Codigoproveedor'] ?>">Editar</a>
                                            <a href="eliminar_proveedor.php?codigo=<?= $row['Codigoproveedor'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este proveedor?');">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p>No se encontraron proveedores.</p>
                <?php endif; ?>
            </div>
            <!-- Fin de Sección de Lista de Proveedores -->

            <!-- Sección de Lista de Servicios -->
            <div id="servicios" style="display: none;">
                <h2>Lista de servicios</h2>
                <a href="#" onclick="ocultarSecciones();">Regresar</a>
                <a href="formulario_servicios.html" class="button">Agregar servicio</a>
                <?php if ($resultado_servicios->num_rows > 0) : ?>
                    <div class="list-container">
                        <table class="user-table">
                            <thead>
                                <tr>
                                    <th>Código del servicio</th>
                                    <th>Nombre del servicio</th>
                                    <th>Tipo de servicio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $resultado_servicios->fetch_assoc()) : ?>
                                    <tr>
                                        <td><?= $row["Codigo"] ?></td>
                                        <td><?= $row["Nombre_servicio"] ?></td>
                                        <td><?= $row["Tipo_servicio"] ?></td>
                                        <td>
                                            <a href="editar_servicio.php?codigo=<?= $row['Codigo'] ?>">Editar</a> |
                                            <a href="eliminar_servicio.php?codigo=<?= $row['Codigo'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este servicio?');">Eliminar</a> |
                                            <a href="activar_servicio.php?codigo=<?= $row['Codigo'] ?>">Activar</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else : ?>
                    <p>No se encontraron servicios.</p>
                <?php endif; ?>
            </div>
            <!-- Fin de Sección de Lista de Servicios -->

           <!-- Sección de Lista de Productos -->
           <div id="productos" style="display: none;">
    <h2>Lista de productos en inventario</h2>
    <a href="#" onclick="ocultarSecciones();" class="button">Regresar</a>
    <a href="formulario.php" class="button">Agregar producto</a>
    <div class="search-container">
        <input type="text" id="buscarProducto" onkeyup="buscarProducto()" placeholder="Buscar producto...">
        <button onclick="buscarProducto()">Buscar</button>
    </div>
    
    <div style="overflow-x: auto; margin: 20px 0;">
        <table class="user-table" style="margin: 0 auto; border-collapse: collapse; width: 100%;">
            <thead>
                <tr style="text-align: left;">
                    <th>Código</th>
                    <th>Nombre</th>
                    <th>Fabricante</th>
                    <th>Tipo</th>
                    <th>Especificaciones</th>
                    <th>Cantidad</th>
                    <th>Precio de Compra</th>
                    <th>Precio de Venta</th>
                    <th>Ganancia</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Stock Mínimo</th>
                    <th>Stock Máximo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultado_productos_inventario->fetch_assoc()) : 
                    $precio_compra = $row["Precio"];
                    $precio_venta = $row["precio_venta"]; // Utilizar el precio de venta directamente de la base de datos
                    $ganancia = $precio_venta - $precio_compra; // Cálculo de la ganancia
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row["Codigoproducto"]) ?></td>
                        <td><?= htmlspecialchars($row["Nombre_producto"]) ?></td>
                        <td><?= htmlspecialchars($row["Fabricante"]) ?></td>
                        <td><?= htmlspecialchars($row["Tipo_producto"]) ?></td>
                        <td><?= htmlspecialchars($row["Especificaciones"]) ?></td>
                        <td><?= htmlspecialchars($row["Cantidad_producto"]) ?></td>
                        <td><?= number_format($precio_compra, 2) ?></td>
                        <td><?= number_format($precio_venta, 2) ?></td>
                        <td><?= number_format($ganancia, 2) ?></td>
                        <td><?= htmlspecialchars($row["Entrada"]) ?></td>
                        <td><?= htmlspecialchars($row["Salida"]) ?></td>
                        <td><?= htmlspecialchars($row["stock_minimo"]) ?></td>
                        <td><?= htmlspecialchars($row["stock_maximo"]) ?></td>
                        <td>
                            <a href="editar_producto.php?codigo=<?= htmlspecialchars($row['Codigoproducto']) ?>" class="button">Editar</a>
                            <a href="eliminar_producto.php?codigo=<?= htmlspecialchars($row['Codigoproducto']) ?>" class="button" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Fin de Sección de Lista de Productos -->

            <!-- Sección de Facturas -->
            <div id="facturas" style="display: none;">
                <h2>Facturas</h2>
                <a href="#" onclick="ocultarSecciones();">Regresar</a>
                <a href="formulario_factura.html" class="button">Agregar factura</a>
                <input type="text" id="buscarFactura" placeholder="Buscar factura..." onkeyup="buscarFactura()">
                <!-- Aquí puedes añadir el código para mostrar las facturas existentes si es necesario -->
            </div>
            <!-- Fin de Sección de Facturas -->

            <!-- Sección de Compras -->
            <div id="compras" style="display: none;">
    <h2>Compras realizadas</h2>
    <a href="#" onclick="ocultarSecciones();">Regresar</a>
    <?php if ($resultado_compras->num_rows > 0) : ?>
        <div class="list-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID Compra</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>productos</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado_compras->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row["id_compra"]) ?></td>
                            <td><?= htmlspecialchars($row["fecha_compra"]) ?></td>
                            <td><?= htmlspecialchars($row["Nombres"] . " " . $row["Apellidos"]) ?></td>
                            <td><?= htmlspecialchars($row["Productos"] . " " . $row["Productos"]) ?></td>
                            <td><?= htmlspecialchars($row["total"]) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <p>No se encontraron compras.</p>
    <?php endif; ?>
</div>
            <!-- Fin de Sección de Compras -->
            </div>
    </div>
</body>
</html>
            </div>
            <!-- Fin de Sección de Compras -->

            <!-- Sección de Devoluciones -->
            <div id="devoluciones" style="display: none;">
                <h2>Formulario de Devoluciones</h2>
                <a href="#" onclick="ocultarSecciones();">Regresar</a>
                <a href="formulario_devoluciones.html" class="button">Formulario de Devolución</a>
            </div>
            <!-- Fin de Sección de Devoluciones -->
        </div>
    </div>
</body>

</html>


$sql_compras = "SELECT c.id_compra, c.fecha_compra, cl.Nombres, cl.Apellidos, c.total,
                c.Nombre_producto, c.cantidad, c.precio
            FROM compras c
            JOIN cliente cl ON c.id_usuario = cl.CodigoCliente
            LEFT JOIN detalles_compras dc ON c.id_compra = dc.id_compra
            LEFT JOIN producto p ON dc.codigoproducto = p.Codigoproducto
            ORDER BY c.id_compra, p.Nombre_producto";

$resultado_compras = $conex->query($sql_compras);
if ($resultado_compras === false) {
    die("Error en la consulta SQL de compras: " . $conex->error);
}


 <!-- Sección de Lista de Compras -->
 <div id="compras" style="display: none;">
                    <h2>Lista de Compras</h2>
                    <a href="#" onclick="ocultarSecciones();">Regresar</a>
                    <?php if ($resultado_compras->num_rows > 0) : ?>
                        <div class="list-container">
                            <table class="user-table">
                                <thead>
                                    <tr>
                                        <th>ID Compra</th>
                                        <th>Fecha</th>
                                        <th>Cliente</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $resultado_compras->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row["id_compra"]) ?></td>
                                            <td><?= htmlspecialchars($row["fecha_compra"]) ?></td>
                                            <td><?= htmlspecialchars($row["Nombres"] . " " . $row["Apellidos"]) ?></td>
                                            <td><?= htmlspecialchars($row["Nombre_producto"]) ?></td>
                                            <td><?= htmlspecialchars($row["cantidad"]) ?></td>
                                            <td><?= htmlspecialchars($row["precio"]) ?></td>
                                            <td><?= htmlspecialchars($row["total"]) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <p>No se encontraron compras.</p>
                    <?php endif; ?>
                </div>
                <!-- Fin de Sección de Lista de Compras -->