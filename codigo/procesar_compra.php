<?php
session_start();

include('conexion.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['productos']) || empty($data['productos'])) {
    echo json_encode(['success' => false, 'message' => 'No se seleccionaron productos.']);
    exit();
}

$productos = $data['productos'];
$total = 0;

foreach ($productos as $producto) {
    $total += $producto['precio'] * $producto['cantidad'];
}

// Aquí se puede agregar el cálculo del IVA y cualquier otro valor adicional que necesites

$iva = $total * 0.19;  // Ejemplo de cálculo de IVA del 19%
$unidad_valor = $total;  // Ejemplo de unidad de valor, puede ser diferente según tu lógica

// Inserta la factura en la base de datos
$sql = "INSERT INTO factura (Codigocliente, Codigoproducto, Codigo, Total, Unidad_valor, Iva, Unidad) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conex->prepare($sql);
$codigocliente = 1; // Puedes cambiar esto según el cliente seleccionado
$codigoproducto = null; // Si necesitas un valor específico, cámbialo aquí
$codigo = null; // Ajusta según tu lógica
$unidad = 'unidad'; // Ajusta según tu lógica

$stmt->bind_param("iisddds", $codigocliente, $codigoproducto, $codigo, $total, $unidad_valor, $iva, $unidad);

if ($stmt->execute()) {
    $factura_id = $stmt->insert_id;

    foreach ($productos as $producto) {
        $sql = "INSERT INTO detalle_factura (Factura_id, Codigoproducto, Cantidad, Precio) VALUES (?, ?, ?, ?)";
        $stmt = $conex->prepare($sql);
        $stmt->bind_param("iiid", $factura_id, $producto['codigo'], $producto['cantidad'], $producto['precio']);
        $stmt->execute();
    }

    echo json_encode(['success' => true, 'factura_id' => $factura_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al crear la factura: ' . $conex->error]);
}
?>