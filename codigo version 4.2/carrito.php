<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$servicios = isset($_SESSION['servicios']) ? $_SESSION['servicios'] : array();

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
    // Calcular el total de la compra
    $total = 0;
    
    // Calcular total para productos
    foreach ($carrito as $codigo => $item) {
        $cantidad = $item['cantidad'];
        $precio = $item['precio'];
        $total += $precio * $cantidad;
    }
    
    // Insertar la compra en la base de datos
    $id_usuario = $_SESSION['id_usuario'];
    $fecha_compra = date('Y-m-d');
    
    $sql = "INSERT INTO compras (id_usuario, fecha_compra, total) VALUES (?, ?, ?)";
    if ($stmt = $conex->prepare($sql)) {
        $stmt->bind_param("isd", $id_usuario, $fecha_compra, $total);
        if ($stmt->execute()) {
            // Vaciar el carrito después de la compra
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
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .user-panel h1 {
            margin-bottom: 20px;
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

        .regresar {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #9bbec0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .regresar:hover {
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
            <h2>Productos en el Carro</h2>
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
                            echo "<tr>
                                    <td>$codigo</td>
                                    <td>{$row['Nombre_producto']}</td>
                                    <td>
                                        <form method='POST' action='carrito.php' style='display:inline;'>
                                            <input type='number' name='cantidad_producto' value='$cantidad' min='1'>
                                            <input type='hidden' name='codigo_producto' value='$codigo'>
                                            <button type='submit' name='actualizar_producto'>Actualizar</button>
                                        </form>
                                    </td>
                                    <td>$precio</td>
                                    <td>$subtotal</td>
                                    <td>
                                        <form method='POST' action='carrito.php' style='display:inline;'>
                                            <input type='hidden' name='codigo_producto' value='$codigo'>
                                            <button type='submit' name='eliminar_producto'>Eliminar</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "Error en la preparación de la consulta SQL para obtener nombre de producto: " . $conex->error;
                    }
                }
                echo "<tr>
                        <td colspan='4'>Total</td>
                        <td>$total</td>
                        <td></td>
                      </tr>";
                echo "</tbody></table>";
            } else {
                echo "<p>El carrito está vacío.</p>";
            }
            ?>
        </div>

        <div class="list-container">
            <h2>Servicios Solicitados</h2>
            <?php
            if (!empty($servicios)) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código de Servicio</th>
                                <th>Nombre del Servicio</th>
                                <th>Tipo de Servicio</th>
                                <th>Cantidad</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($servicios as $codigo => $cantidad) {
                    $sql = "SELECT Nombre_servicio, Tipo_servicio FROM servicios WHERE Codigo = ?";
                    if ($stmt = $conex->prepare($sql)) {
                        $stmt->bind_param("i", $codigo);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        if ($resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();
                            echo "<tr>
                                    <td>$codigo</td>
                                    <td>{$row['Nombre_servicio']}</td>
                                    <td>{$row['Tipo_servicio']}</td>
                                    <td>$cantidad</td>
                                    <td>
                                        <form method='POST' action='carrito.php'>
                                            <input type='hidden' name='codigo_servicio' value='$codigo'>
                                            <button type='submit' name='eliminar_servicio'>Cancelar Servicio</button>
                                        </form>
                                    </td>
                                  </tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "Error en la preparación de la consulta SQL para obtener nombre de servicio: " . $conex->error;
                    }
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No se han solicitado servicios.</p>";
            }
            ?>
        </div>

        <div class="list-container">
            <h2>Estado de las Solicitudes</h2>
            <p>Aquí podrías mostrar el estado de las solicitudes realizadas, por ejemplo:</p>
            <ul>
                <li>Estado de la orden de productos...</li>
                <li>Estado de la solicitud de servicios...</li>
            </ul>
        </div>

        <form method="POST" action="carrito.php">
            <button type="submit" name="comprar">Comprar</button>
        </form>

        <a class="regresar" href="user.php">Regresar</a>
    </div>
</body>
</html>