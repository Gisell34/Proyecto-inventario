<?php
include('conexion.php');

// Obtener y sanitizar los datos del formulario
$nombre_proveedor = filter_var($_POST['Nombre_proveedor'] ?? '', FILTER_SANITIZE_STRING);
$nit = filter_var($_POST['Nit'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$cantidad_total = filter_var($_POST['Cantidad_total'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
$direccion = filter_var($_POST['direccion'] ?? '', FILTER_SANITIZE_STRING);
$telefono = filter_var($_POST['Telefono'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$correo_electronico = filter_var($_POST['Correo_electronico'] ?? '', FILTER_VALIDATE_EMAIL);
$correo_factura = filter_var($_POST['Correo_factura'] ?? '', FILTER_VALIDATE_EMAIL);

header('Content-Type: application/json');

if ($correo_electronico === false || $correo_factura === false) {
    echo json_encode(['success' => false, 'message' => 'Correo electrónico inválido.']);
    exit;
}

$sql_verificar_producto = $conex->prepare("SELECT p.Codigoproducto FROM producto p LEFT JOIN proveedor pr ON p.Codigoproducto = pr.Codigoproducto WHERE pr.Codigoproducto IS NULL ORDER BY p.Codigoproducto ASC LIMIT 1");
$sql_verificar_producto->execute();
$sql_verificar_producto->bind_result($codigo_producto);
$sql_verificar_producto->fetch();
$sql_verificar_producto->close();

if ($codigo_producto) {
    $sql_proveedor = $conex->prepare("INSERT INTO proveedor (Codigoproducto, Nombre_proveedor, Nit, Cantidad_total, Direccion, Telefono, Correo_electronico, Correo_factura) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    if ($sql_proveedor) {
        $conex->begin_transaction();
        try {
            $sql_proveedor->bind_param("isiiisss", $codigo_producto, $nombre_proveedor, $nit, $cantidad_total, $direccion, $telefono, $correo_electronico, $correo_factura);
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
} else {
    echo json_encode(['success' => false, 'message' => 'No se encontró un código de producto no utilizado.']);
}

$conex->close();
