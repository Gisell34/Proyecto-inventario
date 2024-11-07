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
                            </tr>
                        </thead>
                        <tbody>";
                foreach ($carrito as $codigo => $cantidad) {
                    $sql = "SELECT Nombre_producto FROM producto WHERE Codigoproducto = '$codigo'";
                    $resultado = $conex->query($sql);
                    if ($resultado->num_rows > 0) {
                        $row = $resultado->fetch_assoc();
                        echo "<tr>
                                <td>$codigo</td>
                                <td>{$row['Nombre_producto']}</td>
                                <td>$cantidad</td>
                              </tr>";
                    }
                }
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
                    $sql = "SELECT Nombre_servicio, Tipo_servicio FROM servicios WHERE Codigo = '$codigo'";
                    $resultado = $conex->query($sql);
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

        <a class="regresar" href="user.php">Regresar</a> <!-- Botón de regresar debajo del panel de servicios -->
    </div>
</body>
</html>