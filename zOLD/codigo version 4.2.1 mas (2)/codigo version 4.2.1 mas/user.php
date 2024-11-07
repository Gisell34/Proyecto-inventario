<?php
session_start();

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

// Obtener datos del usuario
$usuario = $_SESSION['usuario']; // Usar la sesión actual
$consulta = "SELECT c.Nombres, c.Apellidos, c.Cedula, c.Direccion, c.Correo_electronico, c.Telefono, c.Ciudad 
             FROM cliente c 
             JOIN usuarios u ON c.CodigoCliente = u.Id_usuario 
             WHERE u.Usuario = ?";
if ($stmt = $conex->prepare($consulta)) {
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta SQL para recuperar datos del usuario: " . $conex->error;
}

$facturas = [];
$mensaje_facturas = "";

// Asegúrate de que $_SESSION['usuario'] esté definido
if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    $nombre_usuario = $_SESSION['usuario'];

    // Consulta para obtener el ID del usuario basado en el nombre de usuario
    $consulta_id_usuario = "SELECT Id_usuario FROM usuarios WHERE Usuario = ?";
    if ($stmt_id_usuario = $conex->prepare($consulta_id_usuario)) {
        $stmt_id_usuario->bind_param("s", $nombre_usuario);
        $stmt_id_usuario->execute();
        $resultado_id_usuario = $stmt_id_usuario->get_result();

        // Verificar si se obtuvo un ID de usuario
        if ($resultado_id_usuario->num_rows > 0) {
            $row_id_usuario = $resultado_id_usuario->fetch_assoc();
            $id_usuario = $row_id_usuario['Id_usuario'];

            // Consulta para obtener facturas del usuario
            $consulta_facturas = "SELECT * FROM facturas WHERE Id_usuario = ?";
            if ($stmt_facturas = $conex->prepare($consulta_facturas)) {
                $stmt_facturas->bind_param("i", $id_usuario);
                $stmt_facturas->execute();
                $resultado_facturas = $stmt_facturas->get_result();

                // Verificar si hay facturas
                if ($resultado_facturas->num_rows > 0) {
                    while ($row_factura = $resultado_facturas->fetch_assoc()) {
                        $facturas[] = $row_factura;
                    }
                } else {
                    $mensaje_facturas = "No posee facturas disponibles para visualizar.";
                }

                $stmt_facturas->close();
            } else {
                echo "Error en la preparación de la consulta SQL para recuperar facturas: " . $conex->error;
            }
        } else {
            $mensaje_facturas = "No se encontró el ID del usuario.";
        }

        $stmt_id_usuario->close();
    } else {
        echo "Error en la preparación de la consulta SQL para recuperar el ID del usuario: " . $conex->error;
    }
} else {
    echo "Error: Nombre de usuario no definido.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Panel de usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
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
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }

        .user-panel {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-panel h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-panel a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background-color: #9bbec0;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .user-panel a:hover {
            background-color: #899da4;
        }

        .perfil-usuario,
        .facturas,
        .carrito {
            display: none;
        }

        .user-section {
            margin-top: 20px;
        }

        .list-container {
            display: none;
            margin-top: 20px;
        }

        .facturas-table {
            width: 100%;
            border-collapse: collapse;
        }

        .facturas-table th,
        .facturas-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .facturas-table th {
            background-color: #f4f4f4;
        }

        .show-btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #7bc0c5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .show-btn:hover {
            background-color: #5a9b9a;
        }

        .back-btn {
            display: inline-block;
            margin: 10px 0;
            padding: 10px 20px;
            background-color: #7bc0c5;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #5a9b9a;
        }
    </style>
    <script>
        function toggleSection(sectionId) {
            var section = document.getElementById(sectionId);
            if (section.style.display === "none" || section.style.display === "") {
                section.style.display = "block";
            } else {
                section.style.display = "none";
            }
        }
    </script>
</head>

<body>
    <header>
        <h1>Bienvenido, <?php echo htmlspecialchars($row['Nombres']); ?></h1>
        <nav>
            <a href="catalogo.php">Catálogo de productos y servicios</a>
            <a href="carrito.php">Mi carrito de compras</a>
            <a href="#" onclick="toggleSection('facturas')">Mis facturas</a>
            <a href="Actualizar_perfil.php">Mis datos (actualización)</a>
            <a href="logout.php">Cerrar sesión</a>
        </nav>
    </header>

    <div class="user-panel">
        <div id="facturas" class="list-container">
            <h1>Mis Facturas</h1>
            <a href="#" class="back-btn" onclick="toggleSection('facturas')">Regresar</a>
            <div id="facturas">
                <?php if (!empty($mensaje_facturas)): ?>
                    <p><?php echo htmlspecialchars($mensaje_facturas); ?></p>
                <?php else: ?>
                    <table class="facturas-table">
                        <thead>
                            <tr>
                                <th>ID Factura</th>
                                <th>Fecha</th>
                                <th>Nombre Cliente</th>
                                <th>Apellido Cliente</th>
                                <th>Productos</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($facturas as $factura): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($factura['ID_Factura']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Fecha']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Nombre_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Apellido_cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Productos']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Cantidad']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Precio']); ?></td>
                                    <td><?php echo htmlspecialchars($factura['Total']); ?></td>
                                    <td>
                                        <a href="ver_factura.php?id=<?php echo htmlspecialchars($factura['ID_Factura']); ?>">Ver Detalles</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>