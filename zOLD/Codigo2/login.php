<?php
session_start();
include('conexion.php');

// Verificar si se envió el formulario de login
if (isset($_POST['submit'])) {
    $usuario = $_POST['Usuario'];
    $password = $_POST['Password'];
    $rol = $_POST['rol'];

    // Consulta SQL para verificar las credenciales
    $consulta = "SELECT * FROM usuarios WHERE Usuario = ? AND Password = ? AND rol = ?";
    $stmt = $conex->prepare($consulta);
    $stmt->bind_param("sss", $usuario, $password, $rol);
    $stmt->execute();
    $resultado = $stmt->get_result();

    // Si las credenciales son correctas, iniciar sesión y redirigir según el rol
    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $rol;
        if ($rol == "Usuario") {
            header("Location: user.php");
        } elseif ($rol == "Administrador") {
            header("Location: admin.php");
        }
        exit();
    } else {
        echo "<script>alert('Credenciales incorrectas');</script>";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #ecedf3;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: rgb(54, 54, 61);
        }

        form {
            background: linear-gradient(to right, #9ecfd3, #9495b9);
            padding: 70px;
            border-radius: 10px;
            box-shadow: 0 8px 8px rgba(0, 0, 0, 0.2);
            text-align: left;
        }

        h2 {
            margin-bottom: 20px;
        }

        .input-wrapper {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="radio"] {
            margin-right: 10px;
        }

        input[type="submit"],
        input[type="button"] {
            background-color: #444552;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px 5px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #666666;
        }

        a {
            color: white;
            text-decoration: underline;
            display: block;
            margin-top: 10px;
        }
    </style>
    <script>
        function redirectToRegister() {
            window.location.href = "registro.html";
        }
    </script>
</head>

<body>
    <form action="login.php" method="POST">
        <h2>Iniciar sesion</h2>
        <div class="input-wrapper">
            <label for="Usuario">Ingresar Usuario:</label>
            <input type="text" name="Usuario" required><br>
        </div>
        <div class="input-wrapper">
            <label for="Password">Ingresar password:</label>
            <input type="password" name="Password" required><br>
        </div>
        <div class="input-wrapper">
            <label for="rol">Rol</label>
            <input type="radio" name="rol" value="Usuario" required> Usuario<br>
            <input type="radio" name="rol" value="Administrador" required> Administrador<br>
        </div>
        <input type="submit" name="submit" value="Ingresar">
        <input type="submit" name="submit" value="Cancelar">
        <a href="cambiar_password.php">Cambiar Contraseña</a> <!-- Enlace para cambiar contraseña -->
        <input type="button" value="Registrarse" onclick="redirectToRegister()">
    </form>
</body>

</html>