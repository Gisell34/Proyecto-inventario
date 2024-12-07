<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (!isset($_GET['codigo'])) {
    $_SESSION['mensaje'] = "Proveedor no encontrado.";
    header("Location: proveedores.php");
    exit();
}

$codigo_proveedor = $_GET['codigo'];

$sql = "SELECT Codigoproveedor, Nombre_proveedor, Telefono, Nit, Direccion, Correo_electronico FROM proveedor WHERE Codigoproveedor = ?";
$stmt = $conex->prepare($sql);
$stmt->bind_param('s', $codigo_proveedor);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    $_SESSION['mensaje'] = "Proveedor no encontrado.";
    header("Location: proveedores.php");
    exit();
}

$proveedor = $resultado->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $nit = $_POST['nit'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];

    if (empty($nombre) || empty($telefono) || empty($nit) || empty($direccion) || empty($correo)) {
        $_SESSION['mensaje'] = "Todos los campos son obligatorios.";
        $_SESSION['mensaje_tipo'] = "error";
    } else {
        $sql_update = "UPDATE proveedor SET Nombre_proveedor = ?, Telefono = ?, Nit = ?, Direccion = ?, Correo_electronico = ? WHERE Codigoproveedor = ?";
        $stmt_update = $conex->prepare($sql_update);
        $stmt_update->bind_param('ssssss', $nombre, $telefono, $nit, $direccion, $correo, $codigo_proveedor);

        if ($stmt_update->execute()) {
            $_SESSION['mensaje'] = "Proveedor actualizado exitosamente.";
            $_SESSION['mensaje_tipo'] = "exito";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar el proveedor: " . $conex->error;
            $_SESSION['mensaje_tipo'] = "error";
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . "?codigo=" . $codigo_proveedor);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Proveedor</title>
    <style>
        /* Estilos del formulario */
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

        /* Estilos de los mensajes flotantes */
        .mensaje {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4caf50;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(238, 231, 231, 0.952);
            z-index: 1000;
            max-width: 300px;
            text-align: center;
            font-size: 16px;
        }

        .mensaje.exito {
            background-color: #4caf50;
            color: rgb(255, 255, 255);
        }

        .mensaje.error {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>

<body>
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div class="mensaje <?= $_SESSION['mensaje_tipo']; ?>" id="mensaje-flotante">
            <?= $_SESSION['mensaje']; ?>
        </div>
        <?php unset($_SESSION['mensaje'], $_SESSION['mensaje_tipo']); ?>
    <?php endif; ?>

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
        <a href="lista-proveedores.php">Volver a la lista de proveedores</a>
    </div>

    <script>
        // Mostrar mensaje flotante por un tiempo determinado
        document.addEventListener("DOMContentLoaded", function() {
            const mensaje = document.getElementById("mensaje-flotante");
            if (mensaje) {
                setTimeout(() => {
                    mensaje.style.display = 'none';
                }, 3000); // Ocultar después de 3 segundos
            }
        });
    </script>
</body>

</html>