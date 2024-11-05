<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar que las claves necesarias están definidas en $_POST
    $required_fields = ['Nombre_producto', 'Fabricante', 'Tipo_producto', 'Especificaciones', 'cantidad', 'precio'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field])) {
            echo "Error: Falta el campo $field en el formulario.";
            exit();
        }
    }

    $nombre_producto = $_POST['Nombre_producto'];
    $fabricante = $_POST['Fabricante'];
    $tipo_producto = $_POST['Tipo_producto'];
    $especificaciones = $_POST['Especificaciones'];
    $cantidad = $_POST['cantidad'];
    $precio_compra = $_POST['precio'];

    // Calcular el precio de venta agregando un 30% al precio de compra
    $precio_venta = $precio_compra * 1.3;

    // Iniciar una transacción
    $conex->begin_transaction();

    try {
        // Buscar el producto por nombre
        $sql_buscar_producto = "SELECT p.Codigoproducto, i.Cantidad_producto, i.Precio, i.Entrada, i.Salida 
                                FROM producto p
                                JOIN inventario i ON p.Codigoproducto = i.Codigoproducto
                                WHERE p.Nombre_producto = ?";
        $stmt_buscar_producto = $conex->prepare($sql_buscar_producto);
        $stmt_buscar_producto->bind_param("s", $nombre_producto);
        $stmt_buscar_producto->execute();
        $resultado_busqueda = $stmt_buscar_producto->get_result();

        if ($resultado_busqueda->num_rows > 0) {
            // El producto existe, obtener datos y actualizar inventario
            $row = $resultado_busqueda->fetch_assoc();
            $codigoproducto = $row['Codigoproducto'];
            $cantidad_existente = $row['Cantidad_producto'];
            $precio_existente = $row['Precio'];
            $entrada_existente = $row['Entrada'];
            $salida_existente = $row['Salida'];

            $cantidad_total = $cantidad_existente + $cantidad;
            $entrada_total = $entrada_existente + $cantidad;

            // Calcular stock mínimo y máximo
            $stock_minimo = ceil($cantidad_total * 0.12);
            $stock_maximo = ceil($cantidad_total * 1.10);

            // Actualizar inventario
            $sql_update_inventario = "UPDATE inventario SET
                Cantidad_producto = ?,
                Precio = ?,
                Entrada = ?,
                existencias_total = ?,
                Stock_minimo = ?,
                Stock_maximo = ?
                WHERE Codigoproducto = ?";
            $stmt_update_inventario = $conex->prepare($sql_update_inventario);
            $stmt_update_inventario->bind_param("idiiiii", $cantidad_total, $precio_venta, $entrada_total, $cantidad_total, $stock_minimo, $stock_maximo, $codigoproducto);
            $stmt_update_inventario->execute();

            echo "Producto y datos de inventario actualizados correctamente.";
        } else {
            // El producto no existe, insertar en la tabla de producto
            $sql_producto = "INSERT INTO producto (Nombre_producto, Fabricante, Tipo_producto, Especificaciones)
                             VALUES (?, ?, ?, ?)";
            $stmt_producto = $conex->prepare($sql_producto);
            $stmt_producto->bind_param("ssss", $nombre_producto, $fabricante, $tipo_producto, $especificaciones);
            $stmt_producto->execute();
            $codigoproducto = $stmt_producto->insert_id;

            // Insertar en la tabla de inventario
            $cantidad_total = $cantidad; // Dado que es un producto nuevo
            $entrada_total = $cantidad;
            $salida_total = 0;

            $stock_minimo = ceil($cantidad_total * 0.12);
            $stock_maximo = ceil($cantidad_total * 1.10);

            $sql_inventario = "INSERT INTO inventario (Codigoproducto, Cantidad_producto, Precio, Entrada, Salida, existencias_total, Stock_minimo, Stock_maximo)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_inventario = $conex->prepare($sql_inventario);
            $stmt_inventario->bind_param("iidiiiii", $codigoproducto, $cantidad_total, $precio_venta, $entrada_total, $salida_total, $cantidad_total, $stock_minimo, $stock_maximo);
            $stmt_inventario->execute();

            echo "Producto y datos de inventario guardados correctamente.";
        }

        // Confirmar la transacción
        $conex->commit();

        if (isset($stmt_producto)) $stmt_producto->close();
        if (isset($stmt_inventario)) $stmt_inventario->close();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conex->rollback();
        echo "Error al guardar el producto y/o inventario: " . $e->getMessage();
    }

    $conex->close();
} else {
    echo "Método de solicitud no válido.";
}
?>

<a href="formulario.php">Volver al formulario</a>