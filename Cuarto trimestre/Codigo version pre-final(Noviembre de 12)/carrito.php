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
            font-weight: bold;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 10px 0;
            text-align: center;
            color: black;
            font-weight: bold;
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
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            font-weight: bold;
        }

        .user-table td form {
            display: inline;
        }

        .user-table td button {
            padding: 5px 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .user-table td button:hover {
            background-color: #899da4;
        }

        .total {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            padding: 10px;
            background-color: #e0f7fa;
            border: 2px solid #00796b;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
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
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
        }

        .btn-principal:hover {
            background-color: #899da4;
        }

        .back-btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
        }

        .back-btn:hover {
            background-color: #5a9b9a;
        }

        .btn-comprar {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 1em;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }

        .btn-comprar:hover {
            background-color: #45a049;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
        }

        .payment-option {
            padding: 10px 0;
        }

        .payment-option input {
            margin-right: 10px;
        }

        .btn-select-payment {
            padding: 10px 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-select-payment:hover {
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
        <button id="openModalBtn" class="btn-comprar">Comprar</button>
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

       
        <!-- Modal -->
        <div id="paymentModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Seleccione un método de pago</h2>
                <form method="POST" action="comprar.php">
                    <label for="fecha_compra">Fecha de Compra:</label>
                    <input type="date" name="fecha_compra" id="fecha_compra" required>
                    <div class="payment-option">
                        <input type="radio" id="nequi" name="metodo_pago" value="Nequi" required>
                        <label for="nequi">Nequi</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="daviplata" name="metodo_pago" value="Daviplata" required>
                        <label for="daviplata">Daviplata</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="tarjeta" name="metodo_pago" value="Tarjeta" required>
                        <label for="tarjeta">Tarjeta de crédito/débito</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="efectivo" name="metodo_pago" value="Efectivo" required>
                        <label for="efectivo">Efectivo</label>
                    </div>
                    <button type="submit" class="btn-select-payment">Confirmar y Comprar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        var modal = document.getElementById("paymentModal");

        var btn = document.getElementById("openModalBtn");

        var span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>