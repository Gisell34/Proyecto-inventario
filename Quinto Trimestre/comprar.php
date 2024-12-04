<?php
session_start();
require 'conexion.php';

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    $_SESSION['mensaje'] = "Error: No estás autorizado para realizar esta acción.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Obtener el ID del usuario
$query = "SELECT Id_usuario FROM usuarios WHERE Usuario = ?";
$stmt = $conex->prepare($query);
if (!$stmt) {
    $_SESSION['mensaje'] = "Error en la consulta de usuario.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['mensaje'] = "Usuario no encontrado.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}

$fila = $result->fetch_assoc();
$idUsuario = $fila['Id_usuario'];

// Obtener datos del cliente
$queryCliente = "SELECT Nombres, Apellidos FROM cliente WHERE Codigocliente = ?";
$stmtCliente = $conex->prepare($queryCliente);
if (!$stmtCliente) {
    $_SESSION['mensaje'] = "Error en la consulta de cliente.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}
$stmtCliente->bind_param('i', $idUsuario);
$stmtCliente->execute();
$resultCliente = $stmtCliente->get_result();

if ($resultCliente->num_rows === 0) {
    $_SESSION['mensaje'] = "Datos del cliente no encontrados.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}

$cliente = $resultCliente->fetch_assoc();

// Validar y procesar la fecha de compra
if (isset($_POST['fecha_compra']) && !empty($_POST['fecha_compra'])) {
    $fechaCompra = DateTime::createFromFormat('Y-m-d', $_POST['fecha_compra']);
    if (!$fechaCompra) {
        $_SESSION['mensaje'] = "Formato de fecha inválido.";
        $_SESSION['tipo_mensaje'] = "error";
        header("Location: carrito.php");
        exit();
    }
    $fechaCompra = $fechaCompra->format('Y-m-d');
} else {
    $_SESSION['mensaje'] = "La fecha de compra no está definida.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}

// Validar y procesar el método de pago
if (isset($_POST['metodo_pago']) && !empty($_POST['metodo_pago'])) {
    $metodoPago = $_POST['metodo_pago'];
} else {
    $_SESSION['mensaje'] = "El método de pago no está definido.";
    $_SESSION['tipo_mensaje'] = "error";
    header("Location: carrito.php");
    exit();
}

// Procesar carrito de productos
function procesarCarrito($carrito) {
    $productos = [];
    $cantidades = [];
    $precios = [];

    foreach ($carrito as $item) {
        $productos[] = $item['nombre'];
        $cantidades[] = $item['cantidad'];
        $precios[] = $item['precio'];
    }

    return [
        'productos' => implode(', ', $productos),
        'cantidades' => implode(', ', $cantidades),
        'precios' => implode(', ', $precios)
    ];
}

// Procesar servicios
function procesarServicios($servicios, $conexion) {
    $nombres = [];
    $tipos = [];
    $precios = [];

    foreach ($servicios as $codigo => $cantidad) {
        $query = "SELECT Nombre_servicio, Tipo_servicio, Precio_venta FROM servicios WHERE Codigo = ?";
        $stmt = $conexion->prepare($query);
        if ($stmt) {
            $stmt->bind_param('i', $codigo);
            $stmt->execute();
            $resultado = $stmt->get_result();
            if ($resultado->num_rows > 0) {
                $servicio = $resultado->fetch_assoc();
                $nombres[] = $servicio['Nombre_servicio'];
                $tipos[] = $servicio['Tipo_servicio'];
                $precios[] = $servicio['Precio_venta'];
            }
            $stmt->close();
        }
    }

    return [
        'nombres' => implode(', ', $nombres),
        'tipos' => implode(', ', $tipos),
        'precios' => implode(', ', $precios)
    ];
}

$carritoProcesado = procesarCarrito($_SESSION['carrito']);
$serviciosProcesados = procesarServicios($_SESSION['servicios'], $conex);

// Calcular el total
$total = array_sum(array_map(function ($item) {
    return $item['cantidad'] * $item['precio'];
}, $_SESSION['carrito']));

$totalServicios = array_sum(array_map(function ($codigo) use ($conex) {
    $query = "SELECT Precio_venta FROM servicios WHERE Codigo = ?";
    $stmt = $conex->prepare($query);
    $stmt->bind_param('i', $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $precio = $resultado->fetch_assoc()['Precio_venta'];
    $stmt->close();
    return $precio;
}, array_keys($_SESSION['servicios'])));

$total += $totalServicios;

// Insertar en facturas
$queryFactura = "INSERT INTO facturas (id_usuario, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Nombre_servicio, Tipo_servicio, precio_venta, Total, Metodo_pago) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmtFactura = $conex->prepare($queryFactura);

if ($stmtFactura) {
    $stmtFactura->bind_param(
        'issssssssdds',
        $idUsuario,
        $fechaCompra,
        $cliente['Nombres'],
        $cliente['Apellidos'],
        $carritoProcesado['productos'],
        $carritoProcesado['cantidades'],
        $carritoProcesado['precios'],
        $serviciosProcesados['nombres'],
        $serviciosProcesados['tipos'],
        $serviciosProcesados['precios'],
        $total,
        $metodoPago
    );

    if ($stmtFactura->execute()) {
        $_SESSION['mensaje'] = "Compra registrada exitosamente.";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al registrar la compra.";
        $_SESSION['tipo_mensaje'] = "error";
    }

    $stmtFactura->close();
} else {
    $_SESSION['mensaje'] = "Error en la preparación de la consulta de factura.";
    $_SESSION['tipo_mensaje'] = "error";
}

// Limpiar carrito y servicios
unset($_SESSION['carrito']);
unset($_SESSION['servicios']);

header("Location: carrito.php");
exit();
?>