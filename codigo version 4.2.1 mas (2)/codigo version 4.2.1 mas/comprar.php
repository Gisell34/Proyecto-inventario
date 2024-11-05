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
$stmt->bind_param('s', $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die('No se encontró el ID del usuario.'); // En caso de que no se encuentre el usuario
}

$fila = $result->fetch_assoc();
$Id_usuario = $fila['Id_usuario']; // Obtén el ID del usuario

$fecha_compra = date('Y-m-d'); // Obtén la fecha actual
$total = 0; // Inicializa el total de la compra

// Verifica que el carrito no esté vacío
if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
    die("No hay productos en el carrito.");
}

// Inserta una nueva compra
$query = "INSERT INTO compras (Id_usuario, fecha_compra, total) VALUES (?, ?, ?)";
$stmt = $conex->prepare($query);
if (!$stmt) {
    die("Error en la preparación de la consulta de compra: " . $conex->error);
}
$stmt->bind_param('isd', $Id_usuario, $fecha_compra, $total);

if (!$stmt->execute()) {
    die("Error al insertar la compra: " . $stmt->error);
}

$id_compra = $stmt->insert_id; // Obtén el ID de la compra recién insertada

// Inserta los detalles de la compra
foreach ($_SESSION['carrito'] as $item) {
    $codigoproducto = isset($item['Codigo_producto']) ? $item['Codigo_producto'] : 0;
    $cantidad = isset($item['cantidad']) ? $item['cantidad'] : 0;
    $precio = isset($item['precio']) ? $item['precio'] : 0;
    $tipo_producto = isset($item['tipo_producto']) ? $item['tipo_producto'] : 'producto'; // Default value if missing

    $total += $cantidad * $precio; // Suma el total

    // Depura para verificar los valores antes de la inserción
    echo "Producto: " . (isset($item['Nombre_producto']) ? $item['Nombre_producto'] : 'Desconocido') . ", Cantidad: $cantidad, Precio: $precio\n";

    $query = "INSERT INTO detalles_compras (id_compra, codigoproducto, cantidad, tipo_producto, precio) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conex->prepare($query);
    if (!$stmt) {
        die("Error en la preparación de la consulta de detalles de compra: " . $conex->error);
    }
    $stmt->bind_param('iiisid', $id_compra, $codigoproducto, $cantidad, $tipo_producto, $precio);

    if (!$stmt->execute()) {
        die("Error al insertar detalle de compra: " . $stmt->error);
    }
}

// Actualiza el total de la compra
$query = "UPDATE compras SET total = ? WHERE id_compra = ?";
$stmt = $conex->prepare($query);
if (!$stmt) {
    die("Error en la preparación de la consulta de actualización de total: " . $conex->error);
}
$stmt->bind_param('di', $total, $id_compra);

if (!$stmt->execute()) {
    die("Error al actualizar el total de la compra: " . $stmt->error);
}

// Verifica que el formulario se haya enviado correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['Nombre_cliente']) || !isset($_POST['Apellido_cliente'])) {
        die("Faltan datos del cliente en el formulario.");
    }

    $nombre_cliente = $_POST['Nombre_cliente'];
    $apellido_cliente = $_POST['Apellido_cliente'];
} else {
    die("El formulario no ha sido enviado correctamente.");
}

// Insertar en la tabla facturas
$query = "INSERT INTO facturas (Id_usuario, Fecha, Nombre_cliente, Apellido_cliente, Productos, Cantidad, Precio, Total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conex->prepare($query);
if (!$stmt) {
    die("Error en la preparación de la consulta de facturas: " . $conex->error);
}

// Prepare los datos para la inserción
$productos = '';
$cantidades = 0; // Totalizar cantidades en vez de concatenar
$precios = 0; // Totalizar precios en vez de concatenar

foreach ($_SESSION['carrito'] as $item) {
    $productos .= isset($item['Nombre_producto']) ? $item['Nombre_producto'] . ', ' : 'Desconocido, ';
    $cantidades += isset($item['cantidad']) ? $item['cantidad'] : 0;
    $precios += isset($item['precio']) ? $item['precio'] : 0;
}

// Elimina las últimas comas y espacios
$productos = rtrim($productos, ", ");

$stmt->bind_param('isssidds', $Id_usuario, $fecha_compra, $nombre_cliente, $apellido_cliente, $productos, $cantidades, $precios, $total);

if (!$stmt->execute()) {
    die("Error al insertar la factura: " . $stmt->error);
}

// Vacía el carrito después de la compra
unset($_SESSION['carrito']);
exit();
?>