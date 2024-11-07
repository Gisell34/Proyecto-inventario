<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = array();
}

if (!isset($_SESSION['servicios'])) {
    $_SESSION['servicios'] = array();
}

$carrito = $_SESSION['carrito'];
$servicios = $_SESSION['servicios'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_producto']) && isset($_POST['codigo_producto'])) {
        $codigo = $_POST['codigo_producto'];
        unset($carrito[$codigo]);
        $_SESSION['carrito'] = $carrito;
    }

    if (isset($_POST['eliminar_servicio']) && isset($_POST['codigo_servicio'])) {
        $codigo = $_POST['codigo_servicio'];
        unset($servicios[$codigo]);
        $_SESSION['servicios'] = $servicios;
    }

    if (isset($_POST['actualizar_producto']) && isset($_POST['codigo_producto']) && isset($_POST['cantidad_producto'])) {
        $codigo = $_POST['codigo_producto'];
        $cantidad = $_POST['cantidad_producto'];
        if ($cantidad > 0) {
            $carrito[$codigo]['cantidad'] = $cantidad;
            $_SESSION['carrito'] = $carrito;
        }
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

        .back-btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #7bc0c5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #5a9b9a;
        }

        .btn-comprar {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #4caf50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .btn-comprar:hover {
            background-color: #45a049;
        }
        </style>
</head>
<body>
    <header>
        <h1>Carrito de Compras</h1>
    </header>
    <div class="user-panel">
        <h2>Productos en el carrito:</h2>
        <a href="user.php" class="back-btn">Regresar</a>
        <div class="list-container">
            <?php
            if (!empty($carrito)) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($carrito as $codigo => $item) {
                    echo "<tr>
                            <td>{$codigo}</td>
                            <td>{$item['nombre']}</td>
                            <td>{$item['cantidad']}</td>
                            <td>{$item['precio']}</td>
                            <td>" . ($item['cantidad'] * $item['precio']) . "</td>
                            <td>
                                <form method='POST' action='carrito.php' style='display:inline;'>
                                    <input type='hidden' name='codigo_producto' value='{$codigo}'>
                                    <button type='submit' name='eliminar_producto'>Eliminar</button>
                                </form>
                                <form method='POST' action='carrito.php' style='display:inline;'>
                                    <input type='number' name='cantidad_producto' value='{$item['cantidad']}' min='1' required>
                                    <input type='hidden' name='codigo_producto' value='{$codigo}'>
                                    <button type='submit' name='actualizar_producto'>Actualizar</button>
                                </form>
                            </td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No hay productos en el carrito.</p>";
            }
            ?>
        </div>

        <h2>Servicios en el carrito:</h2>
        <div class="list-container">
            <?php
            if (!empty($servicios)) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($servicios as $codigo => $cantidad) {
                    $sql = "SELECT Nombre_servicio, Precio_venta FROM servicios WHERE Codigo = ?";
                    $stmt = $conex->prepare($sql);
                    if ($stmt) {
                        $stmt->bind_param("i", $codigo);
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        if ($resultado->num_rows > 0) {
                            $row = $resultado->fetch_assoc();
                            $nombre_servicio = $row['Nombre_servicio'];
                            $precio_servicio = $row['Precio_venta'];
                        } else {
                            $nombre_servicio = 'Desconocido';
                            $precio_servicio = 0;
                        }
                        $stmt->close();
                    } else {
                        die("Error en la preparación de la consulta: " . $conex->error);
                    }

                    echo "<tr>
                            <td>{$codigo}</td>
                            <td>{$nombre_servicio}</td>
                            <td>{$precio_servicio}</td>
                            <td>{$precio_servicio}</td>
                            <td>
                                <form method='POST' action='carrito.php' style='display:inline;'>
                                    <input type='hidden' name='codigo_servicio' value='{$codigo}'>
                                    <button type='submit' name='eliminar_servicio'>Eliminar</button>
                                </form>
                            </td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>No hay servicios en el carrito.</p>";
            }
            ?>
        </div>

        <?php
        $total_productos = 0;
        foreach ($carrito as $item) {
            $total_productos += $item['cantidad'] * $item['precio'];
        }
        echo "<div class='total'>Total de productos: $total_productos</div>";

        $total_servicios = 0;
        foreach ($servicios as $codigo => $cantidad) {
            $sql = "SELECT Precio_venta FROM servicios WHERE Codigo = ?";
            $stmt = $conex->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("i", $codigo);
                $stmt->execute();
                $resultado = $stmt->get_result();
                if ($resultado->num_rows > 0) {
                    $row = $resultado->fetch_assoc();
                    $precio_servicio = $row['Precio_venta'];
                    $total_servicios += $precio_servicio;
                }
                $stmt->close();
            }
        }
        echo "<div class='total'>Total de servicios: $total_servicios</div>";

        $total_pagar = $total_productos + $total_servicios;
        echo "<div class='total'>Total a pagar: $total_pagar</div>";
        ?>

        <form method="POST" action="comprar.php">
            <label for="fecha_compra">Fecha de Compra:</label>
            <input type="date" name="fecha_compra" id="fecha_compra" required>
            <button type="submit" name="comprar" class="btn-comprar">Comprar</button>
        </form>
    </div>
</body>
</html>