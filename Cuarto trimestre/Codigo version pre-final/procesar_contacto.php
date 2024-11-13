<?php
session_start();
include('conexion.php'); // Asegúrate de que 'conexion.php' establece la conexión y define $conn

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los campos requeridos están presentes
    if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['mensaje'])) {
        // Obtener y sanitizar los datos del formulario
        $nombre = trim($_POST['nombre']);
        $telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : '';
        $correo = trim($_POST['correo']);
        $mensaje = trim($_POST['mensaje']);

        // Validar los campos requeridos
        if (empty($nombre) || empty($correo) || empty($mensaje)) {
            $_SESSION['error'] = "Por favor, completa todos los campos obligatorios.";
            header("Location: index.html#contacto");
            exit();
        }

        // Validar formato de correo electrónico
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "El correo electrónico no es válido.";
            header("Location: index.html#contacto");
            exit();
        }

        // Preparar y ejecutar la consulta SQL utilizando sentencias preparadas para prevenir inyecciones SQL
        $stmt = $conn->prepare("INSERT INTO contactos (nombre, telefono, correo, mensaje) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            $_SESSION['error'] = "Error en la preparación de la consulta: " . htmlspecialchars($conn->error);
            header("Location: index.html#contacto");
            exit();
        }

        // Vincular parámetros
        $stmt->bind_param("ssss", $nombre, $telefono, $correo, $mensaje);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Éxito: redirigir a la página de agradecimiento
            header("Location: gracias.php");
            exit();
        } else {
            // Error al ejecutar la consulta
            $_SESSION['error'] = "Error al enviar el mensaje: " . htmlspecialchars($stmt->error);
            header("Location: index.html#contacto");
            exit();
        }

        // Cerrar la declaración
        $stmt->close();
    } else {
        $_SESSION['error'] = "Datos del formulario incompletos.";
        header("Location: index.html#contacto");
        exit();
    }
} else {
    // Método de solicitud no válido
    $_SESSION['error'] = "Método de solicitud no válido.";
    header("Location: index.html#contacto");
    exit();
}

// Cerrar la conexión si no se ha cerrado
if ($conn) {
    $conn->close();
}
?>