<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigoProducto = isset($_POST['codigo']) ? $_POST['codigo'] : null;
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;

    if ($codigoProducto === null) {
        echo "Código de producto no proporcionado.";
        exit();
    }

    if (!isset($_SESSION['carrito'])) {
        $_SESSION['carrito'] = array();
    }

    if (array_key_exists($codigoProducto, $_SESSION['carrito'])) {
        $_SESSION['carrito'][$codigoProducto]['cantidad'] += $cantidad;
    } else {
        $_SESSION['carrito'][$codigoProducto] = array(
            'nombre' => '',
            'cantidad' => $cantidad,
            'precio' => 0,
            'tipo' => 'producto'
        );
    }

    $sql = "SELECT Nombre_producto, precio_venta FROM producto JOIN inventario ON producto.Codigoproducto = inventario.Codigoproducto WHERE Codigoproducto = ?";
    if ($stmt = $conex->prepare($sql)) {
        $stmt->bind_param("i", $codigoProducto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $_SESSION['carrito'][$codigoProducto]['nombre'] = $row['Nombre_producto'];
            $_SESSION['carrito'][$codigoProducto]['precio'] = $row['precio_venta'];
        }
        $stmt->close();
    }

    $_SESSION['mensaje'] = "Producto agregado al carrito.";
    header("Location: carrito.php");
}
?>