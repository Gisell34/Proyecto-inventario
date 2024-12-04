<?php
session_start();
include('conexion.php'); // Asegúrate de que este archivo configure correctamente $conn

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si los datos requeridos están presentes
    if (isset($_POST['nombre']) && isset($_POST['correo']) && isset($_POST['mensaje'])) {
        // Sanitizar y validar entradas
        $nombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
        $correo = htmlspecialchars(trim($_POST['correo']), ENT_QUOTES, 'UTF-8');
        $mensaje = htmlspecialchars(trim($_POST['mensaje']), ENT_QUOTES, 'UTF-8');

        // Validar campos vacíos
        if (empty($nombre) || empty($correo) || empty($mensaje)) {
            $_SESSION['error'] = "Por favor, completa todos los campos obligatorios.";
            header("Location: index.php#contactos");
            exit();
        }

        // Validar longitud de campos
        if (strlen($nombre) > 100 || strlen($correo) > 100) {
            $_SESSION['error'] = "El nombre o correo no pueden exceder los 100 caracteres.";
            header("Location: index.php#contactos");
            exit();
        }

        // Validar correo electrónico
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "El correo electrónico no es válido.";
            header("Location: index.php#contactos");
            exit();
        }

        // Preparar consulta
        try {
            $stmt = $conex->prepare("INSERT INTO contactos (nombre, correo, mensaje) VALUES (?, ?, ?)");
            if ($stmt === false) {
                throw new Exception("Error en la preparación de la consulta: " . htmlspecialchars($conn->error));
            }

            // Asociar parámetros y ejecutar
            $stmt->bind_param("sss", $nombre, $correo, $mensaje);

            if ($stmt->execute()) {
                header("Location: gracias.php");
                exit();
            } else {
                throw new Exception("Error al enviar el mensaje: " . htmlspecialchars($stmt->error));
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: index.php#contactos");
            exit();
        } finally {
            if (isset($stmt)) $stmt->close();
        }
    } else {
        $_SESSION['error'] = "Datos del formulario incompletos.";
        header("Location: index.php#contactos");
        exit();
    }
} else {
    $_SESSION['error'] = "Método de solicitud no válido.";
    header("Location: index.php#contactos");
    exit();
}

// Cerrar conexión
if ($conex) {
    $conex->close();
}
?>