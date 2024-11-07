<?php
include('conexion.php');

// Obtener los datos del formulario
$nombre_servicio = $_POST['Nombre_servicio'] ?? '';
$tipo_servicio = $_POST['Tipo_servicio'] ?? '';
$precio_venta = $_POST['Precio_venta'] ?? '';
$estado = $_POST['Estado'] ?? '';

// Validar los datos
$nombre_servicio = trim($nombre_servicio);
$tipo_servicio = trim($tipo_servicio);
$precio_venta = filter_var($precio_venta, FILTER_VALIDATE_FLOAT);
$estado = trim($estado);

if (!$nombre_servicio || !$tipo_servicio || $precio_venta === false || $estado === '') {
    echo '<div class="error-message">Por favor, complete todos los campos correctamente.</div>';
    exit;
}

// Obtener un Id_usuario disponible de la tabla usuarios
$sql_usuario = "SELECT Id_usuario FROM usuarios ORDER BY Id_usuario ASC LIMIT 1";
$result_usuario = $conex->query($sql_usuario);

if ($result_usuario->num_rows > 0) {
    $usuario = $result_usuario->fetch_assoc();
    $id_usuario = $usuario['Id_usuario'];

    // Preparar la consulta SQL para insertar el servicio
    $sql_servicio = $conex->prepare("INSERT INTO servicios (Nombre_servicio, Tipo_servicio, Precio_venta, Id_usuario, Estado) VALUES (?, ?, ?, ?, ?)");

    if ($sql_servicio) {
        $conex->begin_transaction();
        try {
            // Vincular los parámetros y ejecutar la consulta
            $sql_servicio->bind_param("ssdis", $nombre_servicio, $tipo_servicio, $precio_venta, $id_usuario, $estado);
            if ($sql_servicio->execute()) {
                echo '<div class="success-message">Servicio guardado exitosamente.</div>';
            } else {
                throw new Exception("Error al insertar en la tabla 'servicios': " . $sql_servicio->error);
            }

            // Confirmar la transacción
            $conex->commit();
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conex->rollback();
            echo '<div class="error-message">Error al guardar el servicio: ' . $e->getMessage() . '</div>';
        }

        // Cerrar la consulta
        $sql_servicio->close();
    } else {
        echo '<div class="error-message">Error al preparar la consulta: ' . $conex->error . '</div>';
    }
} else {
    echo '<div class="error-message">No se encontró ningún usuario disponible.</div>';
}

// Cerrar la conexión
$conex->close();
?>
<a href="formulario_servicios.html">Volver al formulario</a>
<a href="admin.php">Volver al módulo administrador</a>