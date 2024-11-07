<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'inventariolicorera');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recoger la acción del formulario
    $accion = $_POST['accion'];
    echo "Acción: $accion<br>";

    // Verificar los datos recibidos
    var_dump($_POST);
    echo "<br>";

    if ($accion == "Guardar Producto") {
        $Codigoproducto = $_POST['Codigoproducto'];
        $Fabricante = $_POST['Fabricante'];
        $Tipo_producto = $_POST['Tipo_producto'];
        $Especificaciones = $_POST['Especificaciones'];
        $cantidad = $_POST['cantidad'];
        $nuevo_precio = $_POST['nuevo_precio'];

        // Actualizar el producto existente
        $sql = "UPDATE productos SET Fabricante=?, Tipo_producto=?, Especificaciones=?, Precio_venta=?, Cantidad_producto = Cantidad_producto + ? WHERE Codigoproducto=?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssddi', $Fabricante, $Tipo_producto, $Especificaciones, $nuevo_precio, $cantidad, $Codigoproducto);
            if ($stmt->execute()) {
                echo "Producto actualizado exitosamente";
            } else {
                echo "Error actualizando el producto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparando la consulta: " . $conn->error;
        }
    } elseif ($accion == "Registrar Producto" || $accion == "Registrar y Agregar Otro") {
        $Nombre_producto = $_POST['Nombre_producto'];
        $Fabricante = $_POST['Fabricante_registro'];
        $Tipo_producto = $_POST['Tipo_producto_registro'];
        $Especificaciones = $_POST['Especificaciones_registro'];
        $cantidad = $_POST['cantidad_registro'];
        $Precio = $_POST['Precio'];

        // Insertar el nuevo producto
        $sql = "INSERT INTO productos (Nombre_producto, Fabricante, Tipo_producto, Especificaciones, Cantidad_producto, Precio_venta) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param('sssddi', $Nombre_producto, $Fabricante, $Tipo_producto, $Especificaciones, $cantidad, $Precio);
            if ($stmt->execute()) {
                echo "Producto registrado exitosamente";
            } else {
                echo "Error registrando el producto: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparando la consulta: " . $conn->error;
        }
    }

    // Redirigir a la página para agregar otro si es necesario
    if ($accion == "Guardar y Agregar Otro" || $accion == "Registrar y Agregar Otro") {
        header('Location: registrar_producto.php');
    } else {
        header('Location: admin.php');
    }

    $conn->close();
}
