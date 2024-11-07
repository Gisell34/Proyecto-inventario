<?php
session_start();
include('conexion.php');

// Verificar si la conexión a la base de datos es exitosa
if ($conex->connect_error) {
    die("Error de conexión: " . $conex->connect_error);
}

// Verifica si el carrito está definido
$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_carrito'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $cantidad = $_POST['cantidad'];

    // Inicializa el carrito si no existe para ese tipo
    if (!isset($carrito[$tipo])) {
        $carrito[$tipo] = array();
    }

    // Identifica si es un producto o un servicio
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

            // Agrega el ítem al carrito
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