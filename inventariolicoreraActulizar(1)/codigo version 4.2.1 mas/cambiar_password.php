<?php
session_start();
include('conexion.php');

if (isset($_POST['submit'])) {
    $usuario = $_SESSION['usuario']; // Obtener el usuario actual desde la sesión
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validar que la contraseña nueva y la confirmación coincidan
    if ($newPassword != $confirmPassword) {
        echo "<script>alert('La nueva contraseña y la confirmación no coinciden');</script>";
    } else {
        // Verificar la contraseña actual antes de cambiarla
        $consulta = "SELECT * FROM usuarios WHERE Usuario = ? AND Password = ?";
        $stmt = $conex->prepare($consulta);
        $stmt->bind_param("ss", $usuario, $oldPassword);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            // Actualizar la contraseña en la base de datos
            $updateQuery = "UPDATE usuarios SET Password = ? WHERE Usuario = ?";
            $stmtUpdate = $conex->prepare($updateQuery);
            $stmtUpdate->bind_param("ss", $newPassword, $usuario);
            if ($stmtUpdate->execute()) {
                echo "<script>alert('Contraseña cambiada exitosamente');</script>";
            } else {
                echo "<script>alert('Hubo un problema al cambiar la contraseña');</script>";
            }
        } else {
            echo "<script>alert('La contraseña actual es incorrecta');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
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
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
        }

        form input[type="password"] {
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

        .back-to-login {
            display: block;
            margin-top: 10px;
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-to-login:hover {
            background-color: #899da4;
        }
    </style>
</head>
<body>
    <header>
        <nav>
        <a href="login.php" class="back-to-index">Regresar al ingreso</a>
        </nav>
    </header>

    <div class="admin-panel">
        <h2>Cambiar Contraseña</h2>
        <form action="cambiar_password.php" method="POST">
            <div class="input-wrapper">
                <label for="oldPassword">Contraseña Actual:</label>
                <input type="password" name="oldPassword" required><br>
            </div>
            <div class="input-wrapper">
                <label for="newPassword">Nueva Contraseña:</label>
                <input type="password" name="newPassword" required><br>
            </div>
            <div class="input-wrapper">
                <label for="confirmPassword">Confirmar Nueva Contraseña:</label>
                <input type="password" name="confirmPassword" required><br>
            </div>
            <input type="submit" name="submit" value="Cambiar Contraseña">
        </form>
    </div>
</body>
</html>