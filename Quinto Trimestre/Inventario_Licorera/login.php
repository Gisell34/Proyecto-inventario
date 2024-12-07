<?php
session_start();  // Inicia la sesión (solo una vez)

// Verificar si el formulario de login se ha enviado
if (isset($_POST['submit'])) {
    include('conexion.php'); // Incluye la conexión a la base de datos

    $usuario = $_POST['Usuario'];
    $password = $_POST['Password'];
    $rol = $_POST['rol'];

    // Validación de seguridad: usuario solo alfanumérico y rol solo letras
    if (preg_match("/^[a-zA-Z0-9]+$/", $usuario) && preg_match("/^[a-zA-Z]+$/", $rol)) {
        // Consulta SQL para verificar las credenciales
        $consulta = "SELECT * FROM usuarios WHERE Usuario = ? AND rol = ?";
        $stmt = $conex->prepare($consulta);

        if ($stmt) {
            // Bind y ejecución de la consulta
            $stmt->bind_param("ss", $usuario, $rol);
            $stmt->execute();
            $resultado = $stmt->get_result();

            // Si se encuentra el usuario
            if ($resultado->num_rows > 0) {
                $fila = $resultado->fetch_assoc();

                // Verificar la contraseña usando password_verify()
                if (password_verify($password, $fila['Password'])) {
                    // Si las credenciales son correctas, iniciar sesión
                    $_SESSION['usuario'] = $usuario;  // Asigna el usuario a la sesión
                    $_SESSION['rol'] = $rol;  // Asigna el rol a la sesión

                    // Redirigir según el rol
                    if ($rol == "Administrador") {
                        header("Location: admin.php");  // Redirigir a administrador
                    } else {
                        header("Location: index.php");  // Redirigir a usuarios
                    }
                    exit();  // Asegúrate de que no se ejecute más código después de la redirección
                } else {
                    // Credenciales incorrectas
                    echo "<script>
                            alert('Credenciales incorrectas');
                            window.location.href = 'login.php';  // Redirigir al formulario de login
                          </script>";
                }
            } else {
                // Usuario no encontrado
                echo "<script>
                        alert('Usuario no encontrado');
                        window.location.href = 'login.php';  // Redirigir al formulario de login
                      </script>";
            }

            $stmt->close();  // Cerrar la declaración preparada
        } else {
            // Error en la preparación de la consulta
            echo "<script>
                    alert('Error en la preparación de la consulta');
                    window.location.href = 'login.php';  // Redirigir al formulario de login
                  </script>";
        }
    } else {
        // Usuario o rol contienen caracteres inválidos
        echo "<script>
                alert('El usuario o rol contienen caracteres inválidos');
                window.location.href = 'login.php';  // Redirigir al formulario de login
              </script>";
    }
} else {
    // Mostrar el formulario de login solo si no se ha enviado el formulario
    include('login.html');
}
?>
