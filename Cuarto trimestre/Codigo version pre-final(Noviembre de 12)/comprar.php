<?php
session_start();
require 'conexion.php';

if (!isset($_SESSION['usuario'])) {
    die('No estás autorizado para realizar esta acción. Por favor, inicia sesión.');
}

$usuario = $_SESSION['usuario'];

$query = "SELECT Id_usuario FROM usuarios WHERE Usuario = ?";
$stmt = $conex->prepare($query);
if (!$stmt) {
    die("Error en la preparación de la consulta de usuario: " . $conex->error);
}
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No se encontró el ID del usuario.');
}

$fila = $result->fetch_assoc();
$Id_usuario = $fila['Id_usuario'];

$query_cliente = "SELECT Nombres, Apellidos FROM cliente WHERE Codigocliente = ?";
$stmt_cliente = $conex->prepare($query_cliente);
if (!$stmt_cliente) {
    die("Error en la preparación de la consulta de cliente: " . $conex->error);
}
$stmt_cliente->bind_param('i', $Id_usuario);
$stmt_cliente->execute();
$result_cliente = $stmt_cliente->get_result();

if ($result_cliente->num_rows === 0) {
    die('No se encontraron datos del cliente.');
}

$fila_cliente = $result_cliente->fetch_assoc();
$nombre_cliente = $fila_cliente['Nombres'];
$apellido_cliente = $fila_cliente['Apellidos'];

if (isset($_POST['fecha_compra']) && !empty($_POST['fecha_compra'])) {
    $fecha_compra = DateTime::createFromFormat('Y-m-d', $_POST['fecha_compra']);
    if ($fecha_compra) {
        $fecha_compra = $fecha_compra->format('Y-m-d');
    } else {
        die('Error: Formato de fecha inválido.');
    }
} else {
    die('Error: La fecha de compra no está definida o es inválida.');
}

foreach ($_SESSION['carrito'] as $codigo => $item) {
    $query_compra = "INSERT INTO compras (id_usuario, fecha_compra, Nombre_producto, cantidad, precio, total) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_compra = $conex->prepare($query_compra);
    if (!$stmt_compra) {
        die("Error en la preparación de la consulta de compra: " . $conex->error);
    }
    $total = $item['cantidad'] * $item['precio'];
    $stmt_compra->bind_param('issidd', $Id_usuario, $fecha_compra, $item['nombre'], $item['cantidad'], $item['precio'], $total);
    $stmt_compra->execute();

    $query_inventario = "SELECT entrada, salida FROM inventario WHERE Codigoproducto = ?";
    $stmt_inventario = $conex->prepare($query_inventario);
    if (!$stmt_inventario) {
        die("Error en la preparación de la consulta de inventario: " . $conex->error);
    }
    $stmt_inventario->bind_param('i', $codigo);
    $stmt_inventario->execute();
    $result_inventario = $stmt_inventario->get_result();

    if ($result_inventario->num_rows > 0) {
        $fila_inventario = $result_inventario->fetch_assoc();
        $entrada_actual = $fila_inventario['entrada'];
        $salida_actual = $fila_inventario['salida'];

        $nueva_entrada = $entrada_actual - $item['cantidad'];
        $nueva_salida = $salida_actual + $item['cantidad'];

        $query_actualiza_inventario = "UPDATE inventario SET entrada = ?, salida = ? WHERE Codigoproducto = ?";
        $stmt_actualiza_inventario = $conex->prepare($query_actualiza_inventario);
        if (!$stmt_actualiza_inventario) {
            die("Error en la preparación de la consulta de actualización de inventario: " . $conex->error);
        }
        $stmt_actualiza_inventario->bind_param('iii', $nueva_entrada, $nueva_salida, $codigo);
        $stmt_actualiza_inventario->execute();
    } else {
        die("Error: Producto no encontrado en inventario.");
    }
}

$quantity_total = array_sum(array_column($_SESSION['carrito'], 'cantidad')); 
$precio_total = array_sum(array_map(function ($item) {
    return $item['cantidad'] * $item['precio'];
}, $_SESSION['carrito']));
$total_pagar = $precio_total;
$total_servicios = 0;
foreach ($_SESSION['servicios'] as $codigo => $cantidad) {
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
    } else {
        die("Error en la preparación de la consulta de servicios: " . $conex->error);
    }
}

$total_pagar += $total_servicios;

$productos_factura = '';
foreach ($_SESSION['carrito'] as $codigo => $item) {
    $productos_factura .= $item['nombre'] . ' (' . $item['cantidad'] . ' x ' . $item['precio'] . '), ';
}
$productos_factura = rtrim($productos_factura, ', ');
$servicios_factura = '';
$tipo_servicio = '';

foreach ($_SESSION['servicios'] as $codigo => $cantidad) {
    $sql = "SELECT Nombre_servicio, Tipo_servicio, Precio_venta FROM servicios WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        if ($resultado->num_rows > 0) {
            $row = $resultado->fetch_assoc();
            $nombre_servicio = $row['Nombre_servicio'];
            $tipo_servicio = !empty($row['Tipo_servicio']) ? $row['Tipo_servicio'] : 'No especificado';
            $precio_servicio = $row['Precio_venta'];
            $servicios_factura .= $nombre_servicio . ' (' . $tipo_servicio . ' - ' . $precio_servicio . '), ';
        } else {
            $tipo_servicio = 'No especificado';
        }
        $stmt->close();
    } else {
        die("Error en la preparación de la consulta de servicios: " . $conex->error);
    }
}

$servicios_factura = rtrim($servicios_factura, ', ');

$query_factura = "INSERT INTO facturas (id_usuario, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Nombre_servicio, Tipo_servicio, Precio_venta, Total) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_factura = $conex->prepare($query_factura);
if (!$stmt_factura) {
    die("Error en la preparación de la consulta de factura: " . $conex->error);
}

$stmt_factura->bind_param('issssidssss', $Id_usuario, $fecha_compra, $nombre_cliente, $apellido_cliente, $productos_factura, $quantity_total, $precio_total, $servicios_factura, $tipo_servicio, $total_servicios, $total_pagar);

$stmt_factura->execute();

unset($_SESSION['carrito']);
unset($_SESSION['servicios']);
?>