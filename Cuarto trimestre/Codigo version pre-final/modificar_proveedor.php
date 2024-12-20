<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "SELECT Codigoproveedor, Codigoproducto, Nombre_proveedor, Nit, Cantidad_total, Direccion, Telefono, Correo_electronico, Correo_factura FROM proveedor WHERE Codigoproveedor = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $proveedor = $resultado->fetch_assoc();
    } else {
        echo "Proveedor no encontrado.";
        exit();
    }

    $stmt->close();
} else {
    echo "Código de proveedor no proporcionado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo_producto = $_POST['codigo_producto'];
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $nit = $_POST['nit'];
    $cantidad_total = $_POST['cantidad_total'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo_electronico = $_POST['correo_electronico'];
    $correo_factura = $_POST['correo_factura'];

    $sql = "UPDATE proveedor SET Codigoproducto = ?, Nombre_proveedor = ?, Nit = ?, Cantidad_total = ?, Direccion = ?, Telefono = ?, Correo_electronico = ?, Correo_factura = ? WHERE Codigoproveedor = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("isiiisssi", $codigo_producto, $nombre_proveedor, $nit, $cantidad_total, $direccion, $telefono, $correo_electronico, $correo_factura, $codigo);

    if ($stmt->execute()) {
        echo "Proveedor actualizado exitosamente.";
    } else {
        echo "Error al actualizar el proveedor: " . $conex->error;
    }

    $stmt->close();
}

$conex->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Proveedor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            padding: 10px 0;
            text-align: center;
            color: white;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #899da4;
        }

        .admin-panel {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-panel h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-panel a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .admin-panel a:hover {
            background-color: #899da4;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #899da4;
        }
    </style>
</head>
<body>
    <h1>Modificar Proveedor</h1>
    <form method="POST" action="">
        <label for="codigo_producto">Código Producto:</label>
        <input type="text" id="codigo_producto" name="codigo_producto" value="<?php echo $proveedor['Codigoproducto']; ?>" required>
        <br>
        <label for="nombre_proveedor">Nombre del Proveedor:</label>
        <input type="text" id="nombre_proveedor" name="nombre_proveedor" value="<?php echo $proveedor['Nombre_proveedor']; ?>" required>
        <br>
        <label for="nit">NIT:</label>
        <input type="text" id="nit" name="nit" value="<?php echo $proveedor['Nit']; ?>" required>
        <br>
        <label for="cantidad_total">Cantidad Total:</label>
        <input type="number" id="cantidad_total" name="cantidad_total" value="<?php echo $proveedor['Cantidad_total']; ?>" required>
        <br>
        <label for="direccion">Dirección:</label>
        <input type="text" id="direccion" name="direccion" value="<?php echo $proveedor['Direccion']; ?>" required>
        <br>
        <label for="telefono">Teléfono:</label>
        <input type="text" id="telefono" name="telefono" value="<?php echo $proveedor['Telefono']; ?>" required>
        <br>
        <label for="correo_electronico">Correo Electrónico:</label>
        <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo $proveedor['Correo_electronico']; ?>" required>
        <br>
        <label for="correo_factura">Correo Factura:</label>
        <input type="email" id="correo_factura" name="correo_factura" value="<?php echo $proveedor['Correo_factura']; ?>" required>
        <br>
        <button type="submit">Guardar Cambios</button>
    </form>
    <a href="lista-proveedores.php">Volver a la lista de proveedores</a>
</body>
</html>