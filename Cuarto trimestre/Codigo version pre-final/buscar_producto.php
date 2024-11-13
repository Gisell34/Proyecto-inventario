<?php
include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_producto = $_POST['Nombre_producto'];

    $sql_buscar_producto = "SELECT p.Codigoproducto, p.Fabricante, p.Tipo_producto, p.Especificaciones, i.Cantidad_producto, 
                            i.Precio_venta, i.Entrada, i.salida, i.stock_minimo, i.stock_maximo, i.ganancia
                            FROM producto p 
                            JOIN inventario i ON p.Codigoproducto = i.Codigoproducto 
                            WHERE p.Nombre_producto = ?";
    $stmt_buscar_producto = $conex->prepare($sql_buscar_producto);
    $stmt_buscar_producto->bind_param("s", $nombre_producto);
    $stmt_buscar_producto->execute();
    $resultado_busqueda = $stmt_buscar_producto->get_result();

    if ($resultado_busqueda->num_rows > 0) {
        $producto = $resultado_busqueda->fetch_assoc();
        $producto['existe'] = true;
        $producto['Existencias_totales'] = $producto['Cantidad_producto'];
        $producto['stock_minimo'] = $producto['stock_minimo'];
        $producto['stock_maximo'] = $producto['stock_maximo'];
        $producto['salida'] = $producto['salida'] ?? 0;
        $producto['ganancia'] = $producto['ganancia'] ?? 0;
        $producto['ganancias_totales'] = $producto['Cantidad_producto'] * $producto['ganancia'];
        echo json_encode($producto);
    } else {
        echo json_encode(['existe' => false]);
    }

    $stmt_buscar_producto->close();
    $conex->close();
} else {
    echo json_encode(['error' => 'Método de solicitud no válido.']);
}
