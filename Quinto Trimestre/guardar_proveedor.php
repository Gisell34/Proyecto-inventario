<?php
include('conexion.php');

// Obtener y sanitizar los datos del formulario
$nombre_proveedor = filter_var($_POST['Nombre_proveedor'] ?? '', FILTER_SANITIZE_STRING);
$nit = filter_var($_POST['Nit'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$direccion = filter_var($_POST['Direccion'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$telefono = filter_var($_POST['Telefono'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$correo_electronico = filter_var($_POST['Correo_electronico'] ?? '', FILTER_VALIDATE_EMAIL);
$correo_factura = filter_var($_POST['Correo_factura'] ?? '', FILTER_VALIDATE_EMAIL);

// Registrar datos recibidos para depuración
error_log("POST recibido: " . json_encode($_POST));
error_log("Valor de Dirección: " . (isset($_POST['Direccion']) ? $_POST['Direccion'] : 'No recibido'));

header('Content-Type: application/json');

// Validaciones básicas
if (empty($nombre_proveedor) || empty($direccion)) {
    echo json_encode(['success' => false, 'message' => 'Nombre del proveedor y dirección son obligatorios.']);
    exit;
}

if ($correo_electronico === false || $correo_factura === false) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido.']);
    exit;
}

// Buscar un código de producto no utilizado
$sql_verificar_producto = $conex->prepare(
    "SELECT p.Codigoproducto 
     FROM producto p 
     LEFT JOIN proveedor pr ON p.Codigoproducto = pr.Codigoproducto 
     WHERE pr.Codigoproducto IS NULL 
     ORDER BY p.Codigoproducto ASC 
     LIMIT 1"
);
$sql_verificar_producto->execute();
$sql_verificar_producto->bind_result($codigo_producto);
$sql_verificar_producto->fetch();
$sql_verificar_producto->close();

if (!$codigo_producto) {
    echo json_encode(['success' => false, 'message' => 'No se encontró un código de producto no utilizado.']);
    exit;
}

// Insertar proveedor
$sql_proveedor = $conex->prepare(
    "INSERT INTO proveedor (Codigoproducto, Nombre_proveedor, Nit, Direccion, Telefono, Correo_electronico, Correo_factura) 
     VALUES (?, ?, ?, ?, ?, ?, ?)"
);

if ($sql_proveedor) {
    $conex->begin_transaction();
    try {
        $sql_proveedor->bind_param(
            "isissss", 
            $codigo_producto, 
            $nombre_proveedor, 
            $nit, 
            $direccion, 
            $telefono, 
            $correo_electronico, 
            $correo_factura
        );

        if ($sql_proveedor->execute()) {
            $conex->commit();
            echo json_encode(['success' => true, 'message' => 'Proveedor guardado exitosamente.']);
        } else {
            throw new Exception("Error al insertar en la tabla 'proveedor': " . $sql_proveedor->error);
        }
    } catch (Exception $e) {
        $conex->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al guardar el proveedor: ' . $e->getMessage()]);
    }

    $sql_proveedor->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conex->error]);
}

$conex->close();
?>