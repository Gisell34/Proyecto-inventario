<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

// Verificar si se ha pasado el código del proveedor a través de la URL
if (!isset($_GET['codigo'])) {
    header("Location: proveedores.php");
    exit();
}

$codigo_proveedor = $_GET['codigo'];

// Obtener los datos actuales del proveedor
$sql = "SELECT Codigoproveedor, Nombre_proveedor, Telefono, Nit, Direccion, Correo_electronico FROM proveedor WHERE Codigoproveedor = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param('s', $codigo_proveedor);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    echo "Proveedor no encontrado.";
    exit();
}

$proveedor = $resultado->fetch_assoc();

// Procesar el formulario de edición
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $nit = $_POST['nit'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];

    // Validar los datos
    if (empty($nombre) || empty($telefono) || empty($nit) || empty($direccion) || empty($correo)) {
        echo "Todos los campos son obligatorios.";
    } else {
        // Actualizar los datos del proveedor
        $sql_update = "UPDATE proveedor SET Nombre_proveedor = ?, Telefono = ?, Nit = ?, Direccion = ?, Correo_electronico = ? WHERE Codigoproveedor = ?";
        $stmt_update = $conex->prepare($sql_update);
        $stmt_update->bind_param('ssssss', $nombre, $telefono, $nit, $direccion, $correo, $codigo_proveedor);

        if ($stmt_update->execute()) {
            echo "Proveedor actualizado exitosamente.";
        } else {
            echo "Error al actualizar el proveedor: " . $conex->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h1 {
            text-align: center;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 5px;
        }

        .form-container input {
            margin-bottom: 15px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-container button {
            padding: 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container button:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }

        .form-container a {
            display: inline-block;
            margin-top: 15px;
            color: #7bc0c5;
            text-decoration: none;
        }

        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>Editar Proveedor</h1>
        <form action="" method="post">
            <label for="nombre">Nombre del proveedor:</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($proveedor['Nombre_proveedor']) ?>" required>

            <label for="telefono">Teléfono:</label>
            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($proveedor['Telefono']) ?>" required>

            <label for="nit">NIT:</label>
            <input type="text" id="nit" name="nit" value="<?= htmlspecialchars($proveedor['Nit']) ?>" required>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?= htmlspecialchars($proveedor['Direccion']) ?>" required>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($proveedor['Correo_electronico']) ?>" required>

            <button type="submit">Guardar cambios</button>
        </form>
        <a href="admin.php">Volver a la lista de proveedores</a>
    </div>
</body>

</html>