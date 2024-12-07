<?php
include('conexion.php');

// Configurar el encabezado para JSON
header('Content-Type: application/json');

// Recibir y sanitizar los datos
$nombre_servicio = $_POST['Nombre_servicio'] ?? '';
$tipo_servicio = $_POST['Tipo_servicio'] ?? '';
$precio_venta = $_POST['Precio_venta'] ?? '';
$estado = $_POST['Estado'] ?? '';

$nombre_servicio = trim($nombre_servicio);
$tipo_servicio = trim($tipo_servicio);
$precio_venta = filter_var($precio_venta, FILTER_VALIDATE_FLOAT);
$estado = trim($estado);

// Validar los campos
if (!$nombre_servicio || !$tipo_servicio || $precio_venta === false || $estado === '') {
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos correctamente.']);
    exit;
}

// Consultar el primer usuario disponible
$sql_usuario = "SELECT Id_usuario FROM usuarios ORDER BY Id_usuario ASC LIMIT 1";
$result_usuario = $conex->query($sql_usuario);

if ($result_usuario->num_rows > 0) {
    $usuario = $result_usuario->fetch_assoc();
    $id_usuario = $usuario['Id_usuario'];

    // Preparar la consulta para insertar el nuevo servicio
    $sql_servicio = $conex->prepare("INSERT INTO servicios (Nombre_servicio, Tipo_servicio, Precio_venta, Id_usuario, Estado) VALUES (?, ?, ?, ?, ?)");

    if ($sql_servicio) {
        $conex->begin_transaction();
        try {
            // Vincular parámetros y ejecutar la consulta
            $sql_servicio->bind_param("ssdis", $nombre_servicio, $tipo_servicio, $precio_venta, $id_usuario, $estado);
            if ($sql_servicio->execute()) {
                $conex->commit();
                echo json_encode(['success' => true, 'message' => 'Servicio guardado exitosamente.']);
            } else {
                throw new Exception("Error al insertar en la tabla 'servicio': " . $sql_servicio->error);
            }
        } catch (Exception $e) {
            $conex->rollback();
            echo json_encode(['success' => false, 'message' => 'Error al guardar el servicio: ' . $e->getMessage()]);
        }
        $sql_servicio->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conex->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No se encontró ningún usuario disponible.']);
}

$conex->close();
?>