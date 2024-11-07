<?php

include('conexion.php');

$codigo = $_POST['codigo'];
$nombre = $_POST['nombre'];
$fabricante = $_POST['fabricante'];
$tipo = $_POST['tipo'];
$especificaciones = $_POST['especificaciones'];
$cantidad = $_POST['cantidad'];
$precio = $_POST['precio'];

$sql_inventario = $conex->prepare("INSERT INTO inventario (Codigoproducto, Tipo_producto, Nombre_producto, Cantidad, Precio) VALUES (?, ?, ?, ?, ?)");
$sql_producto = $conex->prepare("INSERT INTO producto (Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones) VALUES (?, ?, ?, ?, ?)");

if ($sql_inventario && $sql_producto) {
    $conex->begin_transaction();

    try {
        // Vincular los parámetros y ejecutar la inserción en la tabla 'inventario'
        $sql_inventario->bind_param("sssdi", $codigo, $tipo, $nombre, $cantidad, $precio);
        if ($sql_inventario->execute()) {
            echo "Producto insertado en la tabla 'inventario'.<br>";
        } else {
            throw new Exception("Error al insertar en la tabla 'inventario': " . $sql_inventario->error);
        }

        // Vincular los parámetros y ejecutar la inserción en la tabla 'producto'
        $sql_producto->bind_param("sssss", $codigo, $nombre, $fabricante, $tipo, $especificaciones);
        if ($sql_producto->execute()) {
            echo "Producto insertado en la tabla 'producto'.<br>";
        } else {
            throw new Exception("Error al insertar en la tabla 'producto': " . $sql_producto->error);
        }

        // Confirmar la transacción
        $conex->commit();
        echo "Producto guardado exitosamente.";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conex->rollback();
        echo "Error al guardar el producto: " . $e->getMessage();
    }

    // Cerrar las declaraciones preparadas
    $sql_inventario->close();
    $sql_producto->close();
} else {
    echo "Error al preparar las consultas: " . $conex->error;
}

$conex->close();
?>