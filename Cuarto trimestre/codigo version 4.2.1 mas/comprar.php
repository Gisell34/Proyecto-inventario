<?php
session_start();
require 'conexion.php';

// Verifica si la sesión está iniciada correctamente
if (!isset($_SESSION['usuario'])) {
    die('No estás autorizado para realizar esta acción. Por favor, inicia sesión.');
}

// Obtiene el nombre de usuario de la sesión
$usuario = $_SESSION['usuario'];

// Consulta para obtener el ID del usuario a partir del nombre de usuario
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
$Id_usuario = $fila['Id_usuario']; // Obtén el ID del usuario

// Consulta para obtener los datos del cliente a partir del ID de usuario
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

// Recibe la fecha de compra
if (isset($_POST['fecha_compra']) && !empty($_POST['fecha_compra'])) {
    // Formatear la fecha si viene en un formato diferente
    $fecha_compra = DateTime::createFromFormat('Y-m-d', $_POST['fecha_compra']);
    if ($fecha_compra) {
        $fecha_compra = $fecha_compra->format('Y-m-d'); // Convertir a 'YYYY-MM-DD' compatible con MySQL
    } else {
        die('Error: Formato de fecha inválido.');
    }
} else {
    die('Error: La fecha de compra no está definida o es inválida.');
}

// Inserta datos en la tabla de compras y actualiza inventario
foreach ($_SESSION['carrito'] as $codigo => $item) {
    // Inserta compra
    $query_compra = "INSERT INTO compras (id_usuario, fecha_compra, Nombre_producto, cantidad, precio, total) 
                     VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_compra = $conex->prepare($query_compra);
    if (!$stmt_compra) {
        die("Error en la preparación de la consulta de compra: " . $conex->error);
    }
    $total = $item['cantidad'] * $item['precio'];
    $stmt_compra->bind_param('issidd', $Id_usuario, $fecha_compra, $item['nombre'], $item['cantidad'], $item['precio'], $total);
    $stmt_compra->execute();

    // Actualiza inventario solo para la cantidad del producto específico
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

        // Resta de entrada y suma a salida solo la cantidad específica de este producto
        $nueva_entrada = $entrada_actual - $item['cantidad'];
        $nueva_salida = $salida_actual + $item['cantidad'];

        // Actualiza inventario
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

// Calcula el total de productos y precios antes de la inserción de facturas
$quantity_total = array_sum(array_column($_SESSION['carrito'], 'cantidad')); 
$precio_total = array_sum(array_map(function ($item) {
    return $item['cantidad'] * $item['precio'];
}, $_SESSION['carrito']));
$total_pagar = $precio_total;

// Calcula el total de servicios
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

// Suma el total de servicios al total a pagar
$total_pagar += $total_servicios;

// Inserta datos en la tabla de facturas
$productos_factura = '';
foreach ($_SESSION['carrito'] as $codigo => $item) {
    $productos_factura .= $item['nombre'] . ' (' . $item['cantidad'] . ' x ' . $item['precio'] . '), ';
}
$productos_factura = rtrim($productos_factura, ', ');

// Si hay servicios, agrégalos a la factura
$servicios_factura = '';
$tipo_servicio = ''; // Inicializa el tipo de servicio por defecto

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
            $tipo_servicio = !empty($row['Tipo_servicio']) ? $row['Tipo_servicio'] : 'No especificado'; // Asigna un valor por defecto si es NULL
            $precio_servicio = $row['Precio_venta'];
            $servicios_factura .= $nombre_servicio . ' (' . $tipo_servicio . ' - ' . $precio_servicio . '), ';
        } else {
            $tipo_servicio = 'No especificado'; // Valor por defecto
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

// Ejecuta la consulta
$stmt_factura->execute();

// Limpia el carrito después de la compra
unset($_SESSION['carrito']);
unset($_SESSION['servicios']);

// Mensaje flotante de éxito
$message = "Compra realizada exitosamente.";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <style>
        /* Estilo básico para el mensaje flotante */
        .floating-message {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            font-size: 16px;
            display: none; /* Inicialmente oculto */
        }
    </style>
</head>
<body>

<div class="floating-message" id="floatingMessage">
    <?php echo $message; ?>
</div>

<script>
    // Muestra el mensaje flotante
    document.getElementById('floatingMessage').style.display = 'block';

    // Oculta el mensaje después de 5 segundos
    setTimeout(function() {
        document.getElementById('floatingMessage').style.display = 'none';
    }, 5000);
</script>

</body>
</html>