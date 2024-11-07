<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$usuario = $_SESSION['usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $cedula = $_POST['cedula'];
    $direccion = $_POST['direccion'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];
    $ciudad = $_POST['ciudad'];

    // Update query
    $sql = "UPDATE cliente c JOIN usuarios u ON c.Codigocliente = u.Id_usuario SET 
            c.Nombres = ?, 
            c.Apellidos = ?, 
            c.Cedula = ?, 
            c.Direccion = ?, 
            c.Correo_electronico = ?, 
            c.Telefono = ?, 
            c.Ciudad = ?
            WHERE u.Usuario = ?";

    if ($stmt = $conex->prepare($sql)) {
        $stmt->bind_param("ssssssss", $nombres, $apellidos, $cedula, $direccion, $correo, $telefono, $ciudad, $usuario);
        if ($stmt->execute()) {
            echo "<script>alert('Perfil actualizado correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "Error al actualizar el perfil: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta SQL para actualizar el perfil: " . $conex->error;
    }
}

$consulta = "SELECT c.Nombres, c.Apellidos, c.Cedula, c.Direccion, c.Correo_electronico, c.Telefono, c.Ciudad FROM cliente c JOIN usuarios u ON c.Codigocliente = u.Id_usuario WHERE u.Usuario = ?";
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
    <title>Actualización de datos de usuario</title>
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
            background-color: #9bbec0;
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #899da4;
        }

        .user-panel {
            max-width: 600px;
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

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            padding: 10px;
            background-color: #9bbec0;
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
    <header>
        <nav>
            <a href="user.php">Volver al panel de usuario</a>
        </nav>
    </header>

    <div class="user-panel">
        <h1>Actualización de datos de usuario</h1>
        <form method="POST" action="actualizar_perfil.php">
            <label>Nombres:</label>
            <input type="text" name="nombres" value="<?php echo isset($row['Nombres']) ? htmlspecialchars($row['Nombres']) : ''; ?>" required><br>
            <label>Apellidos:</label>
            <input type="text" name="apellidos" value="<?php echo isset($row['Apellidos']) ? htmlspecialchars($row['Apellidos']) : ''; ?>" required><br>
            <label>Cédula:</label>
            <input type="text" name="cedula" value="<?php echo isset($row['Cedula']) ? htmlspecialchars($row['Cedula']) : ''; ?>" required><br>
            <label>Dirección:</label>
            <input type="text" name="direccion" value="<?php echo isset($row['Direccion']) ? htmlspecialchars($row['Direccion']) : ''; ?>" required><br>
            <label>Correo electrónico:</label>
            <input type="email" name="correo" value="<?php echo isset($row['Correo_electronico']) ? htmlspecialchars($row['Correo_electronico']) : ''; ?>" required><br>
            <label>Teléfono:</label>
            <input type="text" name="telefono" value="<?php echo isset($row['Telefono']) ? htmlspecialchars($row['Telefono']) : ''; ?>" required><br>
            <label>Ciudad:</label>
            <input type="text" name="ciudad" value="<?php echo isset($row['Ciudad']) ? htmlspecialchars($row['Ciudad']) : ''; ?>" required><br>
            <input type="submit" value="Actualizar">
        </form>
    </div>
</body>
</html>