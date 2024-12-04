<?php
include 'conexion.php';
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = $_POST['accion'];

    if ($accion == 'Registrar Producto') {
        $nombre_producto = $_POST['Nombre_producto'] ?? null;
        $fabricante = $_POST['Fabricante'] ?? null;
        $tipo_producto = $_POST['Tipo_producto'] ?? null;
        $especificaciones = $_POST['Especificaciones'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 0;
        $precio = $_POST['Precio'] ?? 0;

        if ($nombre_producto && $fabricante && $tipo_producto && $especificaciones) {
            $query_producto = "INSERT INTO producto (Nombre_producto, Fabricante, Tipo_producto, Especificaciones) VALUES (?, ?, ?, ?)";
            $stmt_producto = $conex->prepare($query_producto);
            $stmt_producto->bind_param('ssss', $nombre_producto, $fabricante, $tipo_producto, $especificaciones);

            if ($stmt_producto->execute()) {
                $codigoproducto = $conex->insert_id;

                $stock_minimo = $cantidad * 0.15;
                $stock_maximo = $cantidad * 0.10;
                $ganancia = $precio * 0.30;
                $precio_venta = $precio + $ganancia;

                $query_inventario = "INSERT INTO inventario (Codigoproducto, Cantidad_producto, Precio, Entrada, Salida, existencias_total, Stock_minimo, Stock_maximo, precio_venta, ganancia) 
                                     VALUES (?, ?, ?, ?, 0, ?, ?, ?, ?, ?)";
                $stmt_inventario = $conex->prepare($query_inventario);
                $stmt_inventario->bind_param('iidiiiiid', $codigoproducto, $cantidad, $precio, $cantidad, $cantidad, $stock_minimo, $stock_maximo, $precio_venta, $ganancia);

                if ($stmt_inventario->execute()) {
                    $mensaje = "Producto registrado correctamente.";
                } else {
                    $mensaje = "Error al registrar el inventario: " . $stmt_inventario->error;
                }
            } else {
                $mensaje = "Error al registrar el producto: " . $stmt_producto->error;
            }
        } else {
            $mensaje = "Error: Faltan datos necesarios para registrar el producto.";
        }
    }

    if ($accion == 'Guardar Producto') {
        $codigoproducto = $_POST['Codigoproducto'] ?? null;
        $fabricante = $_POST['Fabricante'] ?? null;
        $tipo_producto = $_POST['Tipo_producto'] ?? null;
        $especificaciones = $_POST['Especificaciones'] ?? null;
        $cantidad = $_POST['cantidad'] ?? 0;
        $nuevo_precio = $_POST['nuevo_precio'] ?? 0;

        if ($codigoproducto && $fabricante && $tipo_producto && $especificaciones) {
            $stock_minimo = $cantidad * 0.15;
            $stock_maximo = $cantidad * 0.10;
            $ganancia = $nuevo_precio * 0.30;
            $precio_venta = $nuevo_precio + $ganancia;
            $query_producto = "UPDATE producto SET Fabricante=?, Tipo_producto=?, Especificaciones=? WHERE Codigoproducto=?";
            $stmt_producto = $conex->prepare($query_producto);
            $stmt_producto->bind_param('sssi', $fabricante, $tipo_producto, $especificaciones, $codigoproducto);

            if ($stmt_producto->execute()) {
                $query_inventario = "UPDATE inventario SET Cantidad_producto=Cantidad_producto + ?, Precio=?, Entrada=Entrada + ?, Stock_minimo=?, Stock_maximo=?, precio_venta=?, ganancia=? WHERE Codigoproducto=?";
                $stmt_inventario = $conex->prepare($query_inventario);
                $stmt_inventario->bind_param('idididii', $cantidad, $nuevo_precio, $cantidad, $stock_minimo, $stock_maximo, $precio_venta, $ganancia, $codigoproducto);

                if ($stmt_inventario->execute()) {
                    $mensaje = "Producto actualizado correctamente."; 
                } else {
                    $mensaje = "Error al actualizar el inventario: " . $stmt_inventario->error;
                }
            } else {
                $mensaje = "Error al actualizar el producto: " . $stmt_producto->error;
            }
        } else {
            $mensaje = "Error: Faltan datos necesarios para actualizar el producto.";
        }
    }

    $conex->close();
}

// Si hay un mensaje, lo mostramos y luego redirigimos
if ($mensaje) {
    // Mostrar mensaje mediante JavaScript
    echo "<script type='text/javascript'>
            alert('$mensaje');
            // Redirigir después de mostrar el mensaje
            window.location.href = 'lista-productos.php';
          </script>";
}
?>