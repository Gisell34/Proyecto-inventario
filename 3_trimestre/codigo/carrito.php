<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$servicios = isset($_SESSION['servicios']) ? $_SESSION['servicios'] : array();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrito de Compras</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Carrito de Compras</h1>
    <div class="user-panel">
        <a href="user.php">Regresar a panel de usuario</a>
    </div>
    <div class="user-section">
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
                echo "El carrito está vacío.";
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
                              </tr>";
                    }
                }
                echo "</tbody></table>";
            } else {
                echo "No se han solicitado servicios.";
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
    </div>
</body>
</html>