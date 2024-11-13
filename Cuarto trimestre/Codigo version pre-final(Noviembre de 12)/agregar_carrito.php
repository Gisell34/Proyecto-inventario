<?php
session_start();
include('conexion.php');

if ($conex->connect_error) {
    die("Error de conexión: " . $conex->connect_error);
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_carrito'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

    if (!isset($carrito[$tipo])) {
        $carrito[$tipo] = array();
    }

    if ($tipo === 'producto') {
        $consulta = "SELECT p.Nombre_producto, i.precio_venta 
                     FROM producto p 
                     JOIN inventario i ON p.Codigoproducto = i.Codigoproducto 
                     WHERE p.Codigoproducto = ?";
    } elseif ($tipo === 'servicio') {
        $consulta = "SELECT Nombre_servicio, Precio_venta 
                     FROM servicios 
                     WHERE Codigo = ?";
    }

    if ($stmt = $conex->prepare($consulta)) {
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        $stmt->close();

        if ($row) {
            $nombre = $row['Nombre_producto'] ?? $row['Nombre_servicio'];
            $precio = $row['precio_venta'];

            if (isset($carrito[$tipo][$codigo])) {
                $carrito[$tipo][$codigo]['cantidad'] += $cantidad;
            } else {
                $carrito[$tipo][$codigo] = [
                    'nombre' => $nombre,
                    'cantidad' => $cantidad,
                    'precio' => $precio
                ];
            }

            $_SESSION['carrito'] = $carrito;

            echo "<script>alert('Artículo añadido al carrito.');</script>";
        }
    } else {
        echo "Error en la consulta: " . $conex->error;
    }
}
?>