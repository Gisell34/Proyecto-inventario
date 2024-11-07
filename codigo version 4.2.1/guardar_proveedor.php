<?php
include('conexion.php');

// Obtener y sanitizar los datos del formulario
$nombre_proveedor = filter_var($_POST['Nombre_proveedor'] ?? '', FILTER_SANITIZE_STRING);
$nit = filter_var($_POST['Nit'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$cantidad_total = filter_var($_POST['Cantidad_total'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
$direccion = filter_var($_POST['Direccion'] ?? '', FILTER_SANITIZE_STRING);
$telefono = filter_var($_POST['Telefono'] ?? '', FILTER_SANITIZE_NUMBER_INT);
$correo_electronico = filter_var($_POST['Correo_electronico'] ?? '', FILTER_VALIDATE_EMAIL);
$correo_factura = filter_var($_POST['Correo_factura'] ?? '', FILTER_VALIDATE_EMAIL);

// Verificar si se ha recibido un correo electrónico válido
if ($correo_electronico === false || $correo_factura === false) {
    die('<div class="error-message">Correo electrónico inválido.</div>');
}

// Verificar si hay algún Codigoproducto no utilizado en la tabla proveedor
$sql_verificar_producto = $conex->prepare("SELECT p.Codigoproducto 
    FROM producto p
    LEFT JOIN proveedor pr ON p.Codigoproducto = pr.Codigoproducto
    WHERE pr.Codigoproducto IS NULL
    ORDER BY p.Codigoproducto ASC LIMIT 1
");
$sql_verificar_producto->execute();
$sql_verificar_producto->bind_result($codigo_producto);
$sql_verificar_producto->fetch();
$sql_verificar_producto->close();

if ($codigo_producto) {
    // Preparar la consulta SQL para insertar el proveedor
    $sql_proveedor = $conex->prepare("INSERT INTO proveedor (
            Codigoproducto, Nombre_proveedor, Nit, Cantidad_total, Direccion, Telefono, Correo_electronico, Correo_factura
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if ($sql_proveedor) {
        $conex->begin_transaction();
        try {
            // Vincular los parámetros y ejecutar la consulta
            $sql_proveedor->bind_param("isiiisss", $codigo_producto, $nombre_proveedor, $nit, $cantidad_total, $direccion, $telefono, $correo_electronico, $correo_factura);
            if ($sql_proveedor->execute()) {
                echo '<div class="success-message">Proveedor guardado exitosamente.</div>';
            } else {
                throw new Exception("Error al insertar en la tabla 'proveedor': " . $sql_proveedor->error);
            }

            // Confirmar la transacción
            $conex->commit();
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conex->rollback();
            echo '<div class="error-message">Error al guardar el proveedor: ' . $e->getMessage() . '</div>';
        }

        // Cerrar la consulta
        $sql_proveedor->close();
    } else {
        echo '<div class="error-message">Error al preparar la consulta: ' . $conex->error . '</div>';
    }
} else {
    echo '<div class="error-message">No se encontró un código de producto no utilizado. Por favor, agregue un nuevo código de producto.</div>';
}

// Cerrar la conexión
$conex->close();
?>

<a href="formulario_proveedores.html">Volver al formulario</a>
<a href="admin.php">Volver al módulo administrador</a>