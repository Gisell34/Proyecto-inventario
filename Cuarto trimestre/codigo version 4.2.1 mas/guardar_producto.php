<?php
include 'conexion.php'; // Incluye la conexión a la base de datos

// Verifica si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    // Registro de nuevo producto
    if ($accion == 'Registrar Producto') {
        $nombre_producto = $_POST['Nombre_producto'];
        $fabricante = $_POST['Fabricante'];
        $tipo_producto = $_POST['Tipo_producto'];
        $especificaciones = $_POST['Especificaciones'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['Precio'];

        // Insertar en la tabla producto
        $query_producto = "INSERT INTO producto (Nombre_producto, Fabricante, Tipo_producto, Especificaciones) VALUES (?, ?, ?, ?)";
        $stmt_producto = $conex->prepare($query_producto);
        $stmt_producto->bind_param('ssss', $nombre_producto, $fabricante, $tipo_producto, $especificaciones);
        $stmt_producto->execute();
        
        // Obtener el código del producto insertado
        $codigoproducto = $conex->insert_id;

        // Calcular stock mínimo (15%) y máximo (10%)
        $stock_minimo = $cantidad * 0.15;
        $stock_maximo = $cantidad * 0.10;

        // Calcular ganancia (30%)
        $ganancia = $precio * 0.30;

        // Insertar en la tabla inventario
        $query_inventario = "INSERT INTO inventario (Codigoproducto, Cantidad_producto, Precio, Entrada, Salida, existencias_total, Stock_minimo, Stock_maximo, precio_venta, ganancia) 
                             VALUES (?, ?, ?, ?, 0, ?, ?, ?, ?, ?)";
        $stmt_inventario = $conex->prepare($query_inventario);
        $stmt_inventario->bind_param('iidiiiiid', $codigoproducto, $cantidad, $precio, $cantidad, $cantidad, $stock_minimo, $stock_maximo, $precio, $ganancia);
        $stmt_inventario->execute();

        if ($accion == 'Registrar producto') {
        } else {
            echo "Producto registrado correctamente. <a href='admin.php'>Regresar</a>";
        }
    }

    // Edición de producto
    if ($accion == 'Guardar Producto') {
        $codigoproducto = $_POST['Codigoproducto'];
        $fabricante = $_POST['Fabricante'];
        $tipo_producto = $_POST['Tipo_producto'];
        $especificaciones = $_POST['Especificaciones'];
        $cantidad = $_POST['cantidad'];
        $nuevo_precio = $_POST['nuevo_precio'];

        // Calcular stock mínimo y máximo y la ganancia
        $stock_minimo = $cantidad * 0.15;
        $stock_maximo = $cantidad * 0.10;
        $ganancia = $nuevo_precio * 0.30;

        // Actualizar en la tabla producto
        $query_producto = "UPDATE producto SET Fabricante=?, Tipo_producto=?, Especificaciones=? WHERE Codigoproducto=?";
        $stmt_producto = $conex->prepare($query_producto);
        $stmt_producto->bind_param('sssi', $fabricante, $tipo_producto, $especificaciones, $codigoproducto);
        $stmt_producto->execute();

        // Actualizar en la tabla inventario
        $query_inventario = "UPDATE inventario SET Cantidad_producto=?, Precio=?, Entrada=?, Stock_minimo=?, Stock_maximo=?, precio_venta=?, ganancia=? WHERE Codigoproducto=?";
        $stmt_inventario = $conex->prepare($query_inventario);
        $stmt_inventario->bind_param('idididii', $cantidad, $nuevo_precio, $cantidad, $stock_minimo, $stock_maximo, $nuevo_precio, $ganancia, $codigoproducto);
        $stmt_inventario->execute();

        echo "Producto actualizado correctamente. <a href='admin.php'>Regresar</a>";
    }

    $conex->close();
}
?>