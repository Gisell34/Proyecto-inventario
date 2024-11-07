<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $sql = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto WHERE Codigoproducto = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $producto = $resultado->fetch_assoc();
    } else {
        echo "Producto no encontrado.";
        exit();
    }
} else {
    echo "Código de producto no proporcionado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $fabricante = $_POST['fabricante'];
    $tipo = $_POST['tipo'];
    $especificaciones = $_POST['especificaciones'];

    $sql = "UPDATE producto SET Nombre_producto = ?, Fabricante = ?, Tipo_producto = ?, Especificaciones = ? WHERE Codigoproducto = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("sssss", $nombre, $fabricante, $tipo, $especificaciones, $codigo);

    if ($stmt->execute()) {
        echo "Producto actualizado exitosamente.";
    } else {
        echo "Error al actualizar el producto: " . $conex->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Producto</title>
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
        form input[type="number"],
        form textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
        }

        form input[type="submit"],
        form button {
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover,
        form button:hover {
            background-color: #899da4;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="admin.php#productos">Volver a la lista de productos</a>
        </nav>
    </header>

    <div class="admin-panel">
        <h1>Modificar Producto</h1>
        <form method="POST" action="">
            <label for="nombre">Nombre del Producto:</label>
            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($producto['Nombre_producto']); ?>" required>
            
            <label for="fabricante">Fabricante:</label>
            <input type="text" id="fabricante" name="fabricante" value="<?php echo htmlspecialchars($producto['Fabricante']); ?>" required>
            
            <label for="tipo">Tipo de Producto:</label>
            <input type="text" id="tipo" name="tipo" value="<?php echo htmlspecialchars($producto['Tipo_producto']); ?>" required>
            
            <label for="especificaciones">Especificaciones:</label>
            <textarea id="especificaciones" name="especificaciones" required><?php echo htmlspecialchars($producto['Especificaciones']); ?></textarea>
            
            <button type="submit">Guardar Cambios</button>
        </form>
    </div>
</body>
</html>
