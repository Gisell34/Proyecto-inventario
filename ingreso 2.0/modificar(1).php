<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

if (isset($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Obtener los datos del usuario actual
    $sql = "SELECT usuario, rol FROM login WHERE Id = ?";
    $stmt = $conex->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($usuario_actual, $rol_actual);
    $stmt->fetch();
    $stmt->close();

    // Verificar si se ha enviado el formulario de actualización
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $usuario = $_POST['usuario'];
        $rol = $_POST['rol'];

        // Verificar si se ha ingresado una nueva contraseña
        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encriptar la nueva contraseña
            $sql_update = "UPDATE usuarios SET Usuario = ?, rol = ?, Password = ? WHERE Id = ?";
            $stmt_update = $conex->prepare($sql_update);
            $stmt_update->bind_param("sssi", $usuario, $rol, $password, $id_usuario);
        } else {
            // Si no se ha ingresado una nueva contraseña, no actualizarla
            $sql_update = "UPDATE usuarios SET usuario = ?, rol = ? WHERE Id = ?";
            $stmt_update = $conex->prepare($sql_update);
            $stmt_update->bind_param("ssi", $usuario, $rol, $id_usuario);
        }

        if ($stmt_update->execute()) {
            echo "<script>alert('Usuario actualizado exitosamente.'); window.location.href='admin_panel.php';</script>";
        } else {
            echo "<script>alert('Error al actualizar el usuario.');</script>";
        }

        $stmt_update->close();
    }
} else {
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"],
        select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Editar Usuario</h2>
        <form method="post" action="">
            <label for="usuario">Usuario:</label>
            <input type="text" name="usuario" value="<?php echo htmlspecialchars($usuario_actual); ?>" required>

            <label for="rol">Rol:</label>
            <select name="rol" required>
                <option value="Administrador" <?php if ($rol_actual == 'Administrador') echo 'selected'; ?>>Administrador</option>
                <option value="Usuario" <?php if ($rol_actual == 'usuario') echo 'selected'; ?>>Usuario</option>
            </select>

            <label for="password">Nueva Contraseña (opcional):</label>
            <input type="password" name="password">

            <input type="submit" value="Actualizar Usuario">
        </form>
    </div>
</body>

</html>