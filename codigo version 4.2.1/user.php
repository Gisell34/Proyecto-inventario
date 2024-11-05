<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$carrito_productos = isset($_SESSION['carrito_productos']) ? $_SESSION['carrito_productos'] : array();
$carrito_servicios = isset($_SESSION['carrito_servicios']) ? $_SESSION['carrito_servicios'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['eliminar_producto'])) {
        $codigo_producto = $_POST['codigo_producto'];
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            unset($_SESSION['carrito'][$codigo_producto]);
            echo "<script>alert('Producto eliminado del carrito correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo eliminar el producto del carrito.');</script>";
        }
    }

    if (isset($_POST['modificar_producto'])) {
        $codigo_producto = $_POST['codigo_producto'];
        $nueva_cantidad = $_POST['cantidad'];
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            $_SESSION['carrito'][$codigo_producto] = $nueva_cantidad;
            echo "<script>alert('Cantidad modificada correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo modificar la cantidad del producto.');</script>";
        }
    }

    if (isset($_POST['eliminar_servicio'])) {
        $codigo_servicio = $_POST['codigo_servicio'];
        if (isset($carrito_servicios[$codigo_servicio])) {
            unset($carrito_servicios[$codigo_servicio]);
            $_SESSION['carrito_servicios'] = $carrito_servicios;
            echo "<script>alert('Servicio eliminado del carrito correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo eliminar el servicio del carrito.');</script>";
        }
    }

    if (isset($_POST['modificar_servicio'])) {
        $codigo_servicio = $_POST['codigo_servicio'];
        $nueva_cantidad = $_POST['cantidad'];
        if (isset($carrito_servicios[$codigo_servicio])) {
            $carrito_servicios[$codigo_servicio] = $nueva_cantidad;
            $_SESSION['carrito_servicios'] = $carrito_servicios;
            echo "<script>alert('Cantidad modificada correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo modificar la cantidad del servicio.');</script>";
        }
    }
}

if (isset($_POST['comprar'])) {
    $id_usuario = $_SESSION['Id_usuario']; // Ajustar según la sesión
    $fecha_compra = date("Y-m-d");
    $total = 0;

    // Iniciar la transacción
    $conex->begin_transaction();

    try {
        // Insertar la compra
        $sql = "INSERT INTO compras (Id_usuario, fecha_compra, cantidad, precio, total) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conex->prepare($sql)) {
            $stmt->bind_param("isidi", $id_usuario, $fecha_compra, $cantidad, $precio, $total);
            $stmt->execute();
            $id_compra = $stmt->insert_id;
            $stmt->close();
        } else {
            throw new Exception("Error en la preparación de la consulta SQL para guardar la compra: " . $conex->error);
        }

        // Insertar los detalles de la compra de productos
        foreach ($carrito_productos as $codigo => $item) {
            $cantidad = $item['cantidad'];
            $sql = "SELECT precio_venta, Cantidad_producto FROM inventario WHERE Codigoproducto = ?";
            if ($stmt_producto = $conex->prepare($sql)) {
                $stmt_producto->bind_param("i", $codigo);
                $stmt_producto->execute();
                $resultado_producto = $stmt_producto->get_result();
                if ($resultado_producto->num_rows > 0) {
                    $row_producto = $resultado_producto->fetch_assoc();
                    $precio_venta = $row_producto['precio_venta'];
                    $cantidad_producto = $row_producto['Cantidad_producto'];
                    $valor_total = $precio_venta * $cantidad;
                    $total += $valor_total;

                    // Insertar en detalles_compras
                    $sql_detalle = "INSERT INTO detalles_compras (id_compra, codigoproducto, cantidad, precio, tipo_producto) VALUES (?, ?, ?, ?, 'producto')";
                    if ($stmt_detalle = $conex->prepare($sql_detalle)) {
                        $stmt_detalle->bind_param("iidi", $id_compra, $codigo, $cantidad, $precio_venta);
                        $stmt_detalle->execute();
                        $stmt_detalle->close();
                    } else {
                        throw new Exception("Error en la preparación de la consulta SQL para insertar detalles de compra de productos: " . $conex->error);
                    }

                    // Actualizar inventario
                    $sql_inventario = "UPDATE inventario SET Salida = Salida + ?, Cantidad_producto = Cantidad_producto - ? WHERE Codigoproducto = ?";
                    if ($stmt_inventario = $conex->prepare($sql_inventario)) {
                        $stmt_inventario->bind_param("iii", $cantidad, $cantidad, $codigo);
                        $stmt_inventario->execute();
                        $stmt_inventario->close();
                    } else {
                        throw new Exception("Error en la actualización del inventario: " . $conex->error);
                    }
                } else {
                    throw new Exception("Error al obtener precio del producto: " . $conex->error);
                }
                $stmt_producto->close();
            } else {
                throw new Exception("Error en la preparación de la consulta SQL para obtener precio del producto: " . $conex->error);
            }
        }

        // Insertar los detalles de la compra de servicios
        foreach ($carrito_servicios as $codigo => $cantidad) {
            $sql = "SELECT Precio_venta FROM servicios WHERE Codigo = ?";
            if ($stmt_servicio = $conex->prepare($sql)) {
                $stmt_servicio->bind_param("i", $codigo);
                $stmt_servicio->execute();
                $resultado_servicio = $stmt_servicio->get_result();
                if ($resultado_servicio->num_rows > 0) {
                    $row_servicio = $resultado_servicio->fetch_assoc();
                    $precio_venta = $row_servicio['Precio_venta'];
                    $valor_total = $precio_venta * $cantidad;
                    $total += $valor_total;

                    // Insertar en detalles_compras
                    $sql_detalle = "INSERT INTO detalles_compras (id_compra, codigoproducto, cantidad, precio, tipo_producto) VALUES (?, ?, ?, ?, 'servicio')";
                    if ($stmt_detalle = $conex->prepare($sql_detalle)) {
                        $stmt_detalle->bind_param("iidi", $id_compra, $codigo, $cantidad, $precio_venta);
                        $stmt_detalle->execute();
                        $stmt_detalle->close();
                    } else {
                        throw new Exception("Error en la preparación de la consulta SQL para insertar detalles de compra de servicios: " . $conex->error);
                    }
                } else {
                    throw new Exception("Error al obtener precio del servicio: " . $conex->error);
                }
                $stmt_servicio->close();
            } else {
                throw new Exception("Error en la preparación de la consulta SQL para obtener precio del servicio: " . $conex->error);
            }
        }

        // Actualizar el total en la tabla de compras
        $sql_actualizar_total = "UPDATE compras SET total = ? WHERE id_compra = ?";
        if ($stmt_total = $conex->prepare($sql_actualizar_total)) {
            $stmt_total->bind_param("di", $total, $id_compra);
            $stmt_total->execute();
            $stmt_total->close();
        } else {
            throw new Exception("Error en la preparación de la consulta SQL para actualizar el total de la compra: " . $conex->error);
        }

        // Limpiar el carrito después de la compra
        $_SESSION['carrito_productos'] = array();
        $_SESSION['carrito_servicios'] = array();

        // Confirmar la transacción
        $conex->commit();

        echo "<script>alert('Compra realizada correctamente.');</script>";
        echo "<script>window.location.replace('user.php');</script>";
        exit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conex->rollback();
        echo "<script>alert('Error en la compra: " . $e->getMessage() . "');</script>";
    }
}

$consulta = "SELECT c.Nombres, c.Apellidos, c.Cedula, c.Direccion, c.Correo_electronico, c.Telefono, c.Ciudad 
             FROM cliente c 
             JOIN usuarios u ON c.Codigocliente = u.Id_usuario 
             WHERE u.usuario = ?";

if ($stmt = $conex->prepare($consulta)) {
    $stmt->bind_param("s", $_SESSION['usuario']);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta SQL para recuperar datos del usuario: " . $conex->error;
}

// Consulta para obtener las facturas del usuario actual
$query = "SELECT ID_Factura, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Total 
          FROM facturas 
          WHERE Id_usuario = ?";
          $Id_usuario = isset($_SESSION['Id_usuario']) ? $_SESSION['Id_usuario'] : 0;

if ($stmt = $conex->prepare($query)) {
    $stmt->bind_param("i", $Id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    // Verificar si hay resultados
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Mostrar las facturas
            echo "ID Factura: " . htmlspecialchars($row['ID_Factura']) . "<br>";
            echo "Fecha: " . htmlspecialchars($row['Fecha']) . "<br>";
            echo "Nombre Cliente: " . htmlspecialchars($row['Nombre_cliente']) . "<br>";
            echo "Apellido Cliente: " . htmlspecialchars($row['Apellido_cliente']) . "<br>";
            echo "Productos: " . htmlspecialchars($row['Productos']) . "<br>";
            echo "Cantidad: " . htmlspecialchars($row['Cantidad']) . "<br>";
            echo "Precio: " . htmlspecialchars($row['Precio']) . "<br>";
            echo "Total: " . htmlspecialchars($row['Total']) . "<br>";
            echo "<hr>";
        }
    } else {
        echo "No hay facturas disponibles.";
    }
} else {
    echo "Error en la preparación de la consulta: " . $conex->error;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Panel de usuario</title>
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
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }

        .user-panel {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
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
            background-color: #9bbec0;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .user-panel a:hover {
            background-color: #899da4;
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
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            padding: 10px;
            text-align: center;
            border: 3px double #9bbec0;
        }

        .user-table th {
            background-color: #9bbec0;
            color: white;
        }

        .user-table td form {
            display: inline;
        }

        .user-table td button {
            padding: 5px 10px;
            background-color: #9bbec0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .user-table td button:hover {
            background-color: #899da4;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="catalogo.php">Catálogo de Productos y Servicios</a>
            <a href="carrito.php">Carrito de Compras</a>
            <a href="actualizar_perfil.php">Actualizar Perfil</a>
            <a href="user.php?seccion=tu_factura">Tu Factura</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="user-panel">
        <h1>Bienvenido usuario</h1>
        <div class="perfil-usuario">
            <h2>Perfil de usuario</h2>
            <p>Usuario: <?= htmlspecialchars($_SESSION['usuario']); ?></p>
            <p>Nombres: <?= isset($row['Nombres']) ? htmlspecialchars($row['Nombres']) : 'N/A'; ?></p>
            <p>Apellidos: <?= isset($row['Apellidos']) ? htmlspecialchars($row['Apellidos']) : 'N/A'; ?></p>
            <p>Cédula: <?= isset($row['Cedula']) ? htmlspecialchars($row['Cedula']) : 'N/A'; ?></p>
            <p>Dirección: <?= isset($row['Direccion']) ? htmlspecialchars($row['Direccion']) : 'N/A'; ?></p>
            <p>Correo electrónico: <?= isset($row['Correo_electronico']) ? htmlspecialchars($row['Correo_electronico']) : 'N/A'; ?></p>
            <p>Teléfono: <?= isset($row['Telefono']) ? htmlspecialchars($row['Telefono']) : 'N/A'; ?></p>
            <p>Ciudad: <?= isset($row['Ciudad']) ? htmlspecialchars($row['Ciudad']) : 'N/A'; ?></p>
        </div>

        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Visualización de Facturas</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        </head>

        <body>

            <div class="container mt-5">
                <h2 class="text-center mb-4">Lista de Facturas</h2>

                <!-- Botón para ver el archivo PDF de la factura -->
                <div class="mb-3 text-right">
                    <a href="ver_factura.php" class="btn btn-primary">Ver Factura PDF</a>
                </div>

                <!-- Tabla de visualización de facturas -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID factura</th>
                            <th>Fecha de Compra</th>
                            <th>Nombre del cliente</th>
                            <th>Apellido del cliente</th>
                            <th>Productos</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                            <td>" . htmlspecialchars($row['ID_Factura']) . "</td>
                            <td>" . htmlspecialchars($row['Fecha']) . "</td>
                            <td>" . htmlspecialchars($row['Nombre_cliente']) . "</td>
                            <td>" . htmlspecialchars($row['Apellido_cliente']) . "</td>
                            <td>" . htmlspecialchars($row['Productos']) . "</td>
                            <td>" . htmlspecialchars($row['Cantidad']) . "</td>
                            <td>" . htmlspecialchars($row['Precio']) . "</td>
                            <td>" . htmlspecialchars($row['Total']) . "</td>
                          </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No hay facturas disponibles.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </body>

</html>