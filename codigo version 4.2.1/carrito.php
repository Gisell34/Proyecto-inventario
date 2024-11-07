<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$servicios = isset($_SESSION['servicios']) ? $_SESSION['servicios'] : array();

$total = 0;
$cantidad = 1;
$precio = 0;
// Función para eliminar un servicio del carrito
if (isset($_POST['eliminar_servicio'])) {
    $codigo_servicio = $_POST['codigo_servicio'];
    if (array_key_exists($codigo_servicio, $servicios)) {
        unset($servicios[$codigo_servicio]);
        $_SESSION['servicios'] = $servicios;
        header("Location: carrito.php");
        exit();
    }
}

// Función para eliminar un producto del carrito
if (isset($_POST['eliminar_producto'])) {
    $codigo_producto = $_POST['codigo_producto'];
    if (array_key_exists($codigo_producto, $carrito)) {
        unset($carrito[$codigo_producto]);
        $_SESSION['carrito'] = $carrito;
        header("Location: carrito.php");
        exit();
    }
}

// Función para actualizar la cantidad de un producto en el carrito
if (isset($_POST['actualizar_producto'])) {
    $codigo_producto = $_POST['codigo_producto'];
    $nueva_cantidad = $_POST['cantidad_producto'];

    if (array_key_exists($codigo_producto, $carrito)) {
        if ($nueva_cantidad > 0) {
            $carrito[$codigo_producto]['cantidad'] = $nueva_cantidad;
        } else {
            unset($carrito[$codigo_producto]);
        }
        $_SESSION['carrito'] = $carrito;
        header("Location: carrito.php");
        exit();
    }
}

// Función para procesar la compra
if (isset($_POST['comprar'])) {
    $total = 0;

    // Calcular total para productos
    foreach ($carrito as $codigo => $item) {
        $cantidad = $item['cantidad'];
        $precio = $item['precio'];
        $subtotal += $precio * $cantidad;
    }

    // Calcular total para servicios
    foreach ($servicios as $codigo => $cantidad) {
        $sql = "SELECT Precio_venta FROM servicios WHERE Codigo = ?";
        if ($stmt = $conex->prepare($sql)) {
            $stmt->bind_param("i", $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $row = $resultado->fetch_assoc();
                $precio = $row['Precio_venta'];
                $subtotal += $precio;
            }
            $stmt->close();
        } else {
            echo "Error en la preparación de la consulta SQL para obtener precio del servicio: " . $conex->error;
        }
    }

    $Codigocliente = $_SESSION['Id_compra'];
    $fecha_compra = date('Y-m-d');

    $sql = "INSERT INTO compras (Id_usuario, fecha_compra, cantidad, precio, total) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = $conex->prepare($sql)) {
        $stmt->bind_param("iiiii", $Id_usuario, $fecha_compra, $cantidad, $precio, $total);
        if ($stmt->execute()) {
            $id_compra = $stmt->insert_id;

            foreach ($carrito as $codigo => $item) {
                $cantidad = $item['cantidad'];
                $precio = $item['precio'];
                $sql = "INSERT INTO detalles_compras (id_compra, codigoproducto, cantidad, precio) VALUES (?, ?, ?, ?)";
                if ($stmt = $conex->prepare($sql)) {
                    $stmt->bind_param("iidi", $id_compra, $codigo, $cantidad, $precio);
                    $stmt->execute();
                }
            }

            foreach ($servicios as $codigo => $cantidad) {
                $sql = "INSERT INTO detalles_compras (id_compra, codigoproducto, cantidad, precio) VALUES (?, ?, ?, ?)";
                if ($stmt = $conex->prepare($sql)) {
                    $precio = 0;
                    $stmt->bind_param("iidi", $id_compra, $codigo, $cantidad, $precio);
                    $stmt->execute();
                }
            }

            $_SESSION['carrito'] = array();
            $_SESSION['servicios'] = array();
            echo "<script>alert('Compra realizada con éxito.'); window.location.href='carrito.php';</script>";
        } else {
            echo "Error al registrar la compra: " . $conex->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conex->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Carrito de Compras</title>
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

        .user-panel {
            max-width: 1000px;
            margin: auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .list-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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

        .total {
            font-size: 1.5em;
            /* Tamaño de fuente grande */
            font-weight: bold;
            /* Texto en negrilla */
            color: #333;
            /* Color de texto oscuro para contraste */
            margin-top: 20px;
            /* Espacio superior */
            padding: 10px;
            /* Espacio interior */
            background-color: #e0f7fa;
            /* Fondo claro para destacar */
            border: 2px solid #00796b;
            /* Borde para resaltarlo */
            border-radius: 8px;
            /* Bordes redondeados */
            text-align: center;
            /* Centrarse en la página */
        }

        .btn-principal {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #9bbec0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            border: none;
            /* Asegúrate de que no tenga borde */
            cursor: pointer;
            font-size: 1em;
            /* Tamaño de fuente igual */
        }

        .btn-principal:hover {
            background-color: #899da4;
        }
    </style>
</head>

<body>
    <header>
        <h1>Carrito de Compras</h1>
    </header>

    <div class="user-panel">
        <div class="list-container">
            <h2>Productos en el Carrito</h2>
            <?php
            if (!empty($carrito)) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código de Producto</th>
                                <th>Nombre del Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>";
                $total = 0;
                foreach ($carrito as $codigo => $item) {
                    $cantidad = $item['cantidad'];
                    $precio = $item['precio'];
                    $subtotal = $precio * $cantidad;
                    $total += $subtotal;

                    $sql = "SELECT Nombre_producto FROM producto WHERE Codigoproducto = ?";
                    if ($stmt = $conex->prepare($sql)) {
                        $stmt->bind_param("i", $codigo);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        if ($resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();
                            $nombre_producto = $row['Nombre_producto'];
                            echo "<tr>
                                    <td>$codigo</td>
                                    <td>$nombre_producto</td>
                                    <td>
                                        <form method='POST' action='carrito.php'>
                                            <input type='hidden' name='codigo_producto' value='$codigo'>
                                            <input type='number' name='cantidad_producto' value='$cantidad' min='1'>
                                            <button type='submit' name='actualizar_producto'>Actualizar</button>
                                        </form>
                                    </td>
                                    <td>\$$precio</td>
                                    <td>\$$subtotal</td>
                                    <td>
                                        <form method='POST' action='carrito.php'>
                                            <input type='hidden' name='codigo_producto' value='$codigo'>
                                            <button type='submit' name='eliminar_producto'>Eliminar</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "Error en la preparación de la consulta: " . $conex->error;
                    }
                }
                echo "<tr>
                        <td colspan='4'>Total</td>
                        <td colspan='2'>\$$total</td>
                      </tr>
                      </tbody>
                      </table>";
            } else {
                echo "<p>No hay productos en el carrito.</p>";
            }
            ?>
        </div>

        <div class="list-container">
            <h2>Servicios en el Carrito</h2>
            <?php
            if (!empty($servicios)) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código de Servicio</th>
                                <th>Nombre del Servicio</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Subtotal</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>";
                $total = 0;
                foreach ($servicios as $codigo => $cantidad) {
                    $cantidad = 1; // Cantidad fija para servicios
                    $sql = "SELECT Nombre_servicio, Precio_venta FROM servicios WHERE Codigo = ?";
                    if ($stmt = $conex->prepare($sql)) {
                        $stmt->bind_param("i", $codigo);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        if ($resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();
                            $nombre_servicio = $row['Nombre_servicio'];
                            $precio = $row['Precio_venta'];
                            $subtotal = $precio * $cantidad;
                            $total += $subtotal;
                            echo "<tr>
                                    <td>$codigo</td>
                                    <td>$nombre_servicio</td>
                                    <td>$cantidad</td>
                                    <td>\$$precio</td>
                                    <td>\$$subtotal</td>
                                    <td>
                                        <form method='POST' action='carrito.php'>
                                            <input type='hidden' name='codigo_producto' value='$codigo'>
                                            <button type='submit' name='eliminar_producto'>Eliminar</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "Error en la preparación de la consulta: " . $conex->error;
                    }
                }
                echo "<tr>
                        <td colspan='4'>Total</td>
                        <td colspan='2'>\$$total</td>
                      </tr>
                      </tbody>
                      </table>";
            } else {
                echo "<p>No hay productos en el carrito.</p>";
            }
            ?>

            <!-- Mostrar el total a pagar -->
            <div class="total">
                Total a Pagar: $<?php echo number_format($total, 2); ?>
            </div>

            <form method="POST" action="carrito.php">
                <button type="submit" name="comprar" class="btn-principal">Comprar</button>
            </form>

            <a href="user.php" class="btn-principal">Regresar</a>
        </div>
</body>

</html>