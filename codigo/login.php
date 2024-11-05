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