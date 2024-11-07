<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();

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

    if (isset($_POST['comprar_producto'])) {
        $codigo_producto = $_POST['codigo_producto'];
        
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            unset($_SESSION['carrito'][$codigo_producto]);
            echo "<script>alert('Producto comprado correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo comprar el producto.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de Usuario</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Bienvenido usuario</h1>
    <div class="user-panel">
        <a href="ver_productos.php">Lista de Productos</a>
        <a href="ver_servicios.php">Lista de Servicios</a>
        <a href="carrito.php">Carrito de Compras</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
    <div class="user-section">
        <h2>Estado de tus Solicitudes</h2>
        <div class="list-container">
            <h3>Productos en el Carrito</h3>
            <?php
            if (!empty($carrito)) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código de Producto</th>
                                <th>Nombre del Producto</th>
                                <th>Cantidad</th>
                                <th>Acción</th>
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
                                <td>
                                    <form method='POST' action='user.php'>
                                        <input type='hidden' name='codigo_producto' value='$codigo'>
                                        <button type='submit' name='eliminar_producto'>Eliminar</button>
                                    </form>
                                    <form method='POST' action='user.php'>
                                        <input type='hidden' name='codigo_producto' value='$codigo'>
                                        <button type='submit' name='comprar_producto'>Comprar</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                }
                echo "</tbody></table>";
            } else {
                echo "El carrito está vacío.";
            }
            ?>
        </div>
    </div>
</body>
</html>