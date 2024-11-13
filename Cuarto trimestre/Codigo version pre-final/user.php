<?php
session_start();

// Verifica que el usuario esté autenticado
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

// Obtener datos del usuario
$usuario = $_SESSION['usuario'];
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
            font-weight: bold;
        }

        .welcome-message {
            text-align: center;
            margin-top: 50px; /* Ajusta este valor según lo que necesites */
            padding: 0;
            margin-bottom: 10; /* Evita que quede demasiado espacio con el contenido de abajo */
            color:  #444552; /* Asegúrate de que el color sea adecuado según el fondo */
            font-weight: bold;
        }


        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 10px 0;
            text-align: center;
            color: white, black;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: black;
            text-decoration: none;
            padding: 20px 20px;
            border-radius: 8px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            transition: background-color 0.1s ease;
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
    <nav>
        <a href="catalogo.php">Catálogo de productos y servicios</a>
        <a href="carrito.php">Mi carrito de compras</a>
        <a href="lista-facturas-usuario.php">Mis facturas</a>
        <a href="Actualizar_perfil.php">Mis datos (actualización)</a>
        <a href="logout.php">Cerrar sesión</a>
    </nav>
    </header>
    <div class="welcome-container">
        <h1 class="welcome-message">Bienvenido, <?php echo htmlspecialchars($row['Nombres']); ?></h1>
    </div>
</body>

</html>