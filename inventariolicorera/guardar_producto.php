<?php

include('conexion.php');

// Inicializar variables para los datos del formulario
$nombre = $_POST['nombre'] ?? '';
$fabricante = $_POST['fabricante'] ?? '';
$tipo = $_POST['tipo'] ?? '';
$especificaciones = $_POST['especificaciones'] ?? '';
$cantidad = $_POST['cantidad'] ?? 0; // Valor por defecto si no se proporciona
$precio = $_POST['precio'] ?? 0.0; // Valor por defecto si no se proporciona

// Preparar las consultas SQL
$sql_producto = $conex->prepare("INSERT INTO producto (Nombre_producto, Fabricante, Tipo_producto, Especificaciones) VALUES (?, ?, ?, ?)");
$sql_inventario = $conex->prepare("INSERT INTO inventario (Codigoproducto, Cantidad_producto, Precio) VALUES (?, ?, ?)");

if ($sql_producto && $sql_inventario) {
    // Iniciar la transacción
    $conex->begin_transaction();

    try {
        // Vincular los parámetros y ejecutar la inserción en la tabla 'producto'
        $sql_producto->bind_param("sssd", $nombre, $fabricante, $tipo, $especificaciones);
        if ($sql_producto->execute()) {
            // Obtener el último ID insertado en la tabla 'producto'
            $codigo_producto = $conex->insert_id;
            echo "Producto insertado en la tabla 'producto'.<br>";
        } else {
            throw new Exception("Error al insertar en la tabla 'producto': " . $sql_producto->error);
        }

        // Vincular los parámetros y ejecutar la inserción en la tabla 'inventario'
        $sql_inventario->bind_param("idd", $codigo_producto, $cantidad, $precio);
        if ($sql_inventario->execute()) {
            echo "Producto insertado en la tabla 'inventario'.<br>";
        } else {
            throw new Exception("Error al insertar en la tabla 'inventario': " . $sql_inventario->error);
        }

        // Confirmar la transacción
        $conex->commit();
        echo "Producto guardado exitosamente.<br>";

        // Enlace para volver al formulario
        echo '<a href="formulario.php">Volver al formulario</a>';
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conex->rollback();
        echo "Error al guardar el producto: " . $e->getMessage() . "<br>";

        // Enlace para volver al formulario
        echo '<a href="formulario.php">Volver al formulario</a>';
    }

    // Cerrar las declaraciones preparadas
    $sql_producto->close();
    $sql_inventario->close();
} else {
    echo "Error al preparar las consultas: " . $conex->error . "<br>";

    // Enlace para volver al formulario
    echo '<a href="formulario.php">Volver al formulario</a>';
}

// Cerrar la conexión
$conex->close();
?>