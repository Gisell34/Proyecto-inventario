<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "SELECT Codigo, Nombre_servicio, Tipo_servicio, Precio_venta, Estado FROM servicios WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("i", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $servicio = $resultado->fetch_assoc();
    } else {
        echo "Servicio no encontrado.";
        exit();
    }

    $stmt->close();
} else {
    echo "Código de servicio no proporcionado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre_servicio = $_POST['Nombre_servicio'];
    $Tipo_servicio = $_POST['Tipo_servicio'];
    $Precio_venta = $_POST['Precio_venta'];
    $Estado = $_POST['Estado'];

    $sql = "UPDATE servicios SET Nombre_servicio = ?, Tipo_servicio = ?, Precio_venta = ?, Estado = ? WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ssii", $nombre_servicio, $Tipo_servicio, $Precio_venta, $Estado, $codigo);

    if ($stmt->execute()) {
        echo "Servicio actualizado exitosamente.";
    } else {
        echo "Error al actualizar el servicio: " . $conex->error;
    }

    $stmt->close();
}

$conex->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar servicio</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #6fa3ef, #3d8fd1);
            padding: 15px 0;
            text-align: center;
            color: white;
        }

        h1 {
            margin: 20px 0;
            text-align: center;
        }

        .admin-panel {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        form label {
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            padding: 10px;
            background: linear-gradient(to right, #6fa3ef, #3d8fd1);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        form button:hover {
            background-color: #3d8fd1;
        }

        .admin-panel a {
            display: block;
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            background: linear-gradient(to right, #6fa3ef, #3d8fd1);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .admin-panel a:hover {
            background-color: #3d8fd1;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
    </header>
    <div class="admin-panel">
        <h2>Modificar servicio</h2>
        <form method="POST" action="">
            <label for="codigo">Código del servicio:</label>
            <input type="number" id="codigo" name="codigo" value="<?php echo htmlspecialchars($servicio['Codigo']); ?>" required>
            
            <label for="Nombre_servicio">Nombre del servicio:</label>
            <input type="text" id="Nombre_servicio" name="Nombre_servicio" value="<?php echo htmlspecialchars($servicio['Nombre_servicio']); ?>" required>
            
            <label for="Tipo_servicio">Naturaleza del servicio:</label>
            <input type="text" id="Tipo_servicio" name="Tipo_servicio" value="<?php echo htmlspecialchars($servicio['Tipo_servicio']); ?>" required>
            
            <label for="Precio_venta">Costo:</label>
            <input type="number" id="Precio_venta" name="Precio_venta" value="<?php echo htmlspecialchars($servicio['Precio_venta']); ?>" required step="0.01" min="0">
            
            <label for="Estado">Estado del servicio:</label>
            <input type="text" id="Estado" name="Estado" value="<?php echo htmlspecialchars($servicio['Estado']); ?>" required>
            
            <button type="submit">Guardar Cambios</button>
        </form>
        <a href="admin.php">Volver a la lista de servicios</a>
    </div>
</body>
</html>