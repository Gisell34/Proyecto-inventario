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

$mensaje = ""; // Variable para almacenar el mensaje

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $codigo = $_POST['codigo'];
    $nombre_servicio = $_POST['Nombre_servicio'];
    $Tipo_servicio = $_POST['Tipo_servicio'];
    $Precio_venta = $_POST['Precio_venta'];
    $Estado = $_POST['Estado'];

    $sql = "UPDATE servicios SET Nombre_servicio = ?, Tipo_servicio = ?, Precio_venta = ?, Estado = ? WHERE Codigo = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("ssiii", $nombre_servicio, $Tipo_servicio, $Precio_venta, $Estado, $codigo);

    if ($stmt->execute()) {
        $mensaje = "Servicio actualizado exitosamente.";
    } else {
        $mensaje = "Error al actualizar el servicio: " . $conex->error;
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

        form input[type="submit"],
        button[type="submit"] {
        padding: 10px;
        background: linear-gradient(to right, #9495b9, #7bc0c5);
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%; /* Opcional: Si quieres que el botón ocupe todo el ancho disponible */
        }

        form input[type="submit"]:hover,
        button[type="submit"]:hover {
            background-color: #899da4;
        }

        .mensaje-flotante {
            position: absolute;
            top: 20px; /* Ajusta según donde quieras que aparezca */
            left: 50%;
            transform: translateX(-50%);
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: opacity 0.5s ease, transform 0.5s ease;
            opacity: 0;
            transform: translate(-50%, -20px);
        }

        .mensaje-flotante.visible {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    </style>
    <script>
        function mostrarMensaje(mensaje, tipo) {
            const mensajeFlotante = document.getElementById('mensaje-flotante');
            mensajeFlotante.textContent = mensaje;

            // Cambiar el color según el tipo de mensaje (éxito o error)
            mensajeFlotante.style.backgroundColor = tipo === 'exito' ? '#d4edda' : '#f8d7da';
            mensajeFlotante.style.color = tipo === 'exito' ? '#155724' : '#721c24';

            mensajeFlotante.classList.add('visible');

            // Ocultar el mensaje después de 3 segundos
            setTimeout(() => {
                mensajeFlotante.classList.remove('visible');
            }, 3000);
        }

        window.onload = function() {
            <?php if ($mensaje): ?>
                mostrarMensaje("<?php echo addslashes($mensaje); ?>", "<?php echo $mensaje === 'Servicio actualizado exitosamente.' ? 'exito' : 'error'; ?>");
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
    </header>
    <div class="admin-panel">
        <a href="lista-servicios.php">Volver a la lista de servicios</a>
        <h2>Modificar servicio</h2>
        <form action="" method="POST">
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
        <!-- Mensaje flotante -->
        <div id="mensaje-flotante" class="mensaje-flotante"></div>
    </div>
</body>
</html>