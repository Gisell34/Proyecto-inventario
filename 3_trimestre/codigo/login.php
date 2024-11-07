<?php 
session_start();
include('conexion.php');

if(isset($_POST['submit'])) {
    $usuario = $_POST['Usuario'];
    $password = $_POST['Password'];
    $rol = $_POST['rol'];

    $consulta = "SELECT * FROM usuarios WHERE Usuario = ? AND Password = ? AND rol = ?";
    $stmt = $conex->prepare($consulta);
    $stmt->bind_param("sss", $usuario, $password, $rol);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['rol'] = $rol;
        if ($rol == "Usuario") {
            header("Location: user.php");
        } elseif($rol == "Administrador") {
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
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <form action="login.php" method="POST">
        <h2> Registro </h2>
        <div class=" inpunt-wrapper">
        <label for="Usuario">Usuario: </label>
        <input type="text" name="Usuario" required><br>
        </div>
<div class=" inpunt-wrapper">
        <label for="Password">Contraseña:</label>
        <input type="password" name="Password" required><br>
</div>
<div class=" inpunt-wrapper">
        <input type="radio" name="rol" value="Usuario" required> Usuario<br>
        <input type="radio" name="rol" value="Administrador" required> Administrador<br>
</div>
        <input type="submit" name="submit" value="Ingresar">
        <input type="submit" name="submit" value="Cancelar">
    </form>
</body>
</html>