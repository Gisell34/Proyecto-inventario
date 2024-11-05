<?php
include('conexion.php');

// Verificar si se ha enviado el formulario para eliminar el usuario
if (isset($_POST['eliminar'])) {
    $id = $_POST['Id'];

    // Verificar si el ID está vacío o no es un número válido
    if (empty($id) || !is_numeric($id)) {
        echo "<script>alert('ID inválido.'); window.location.href='admin.php';</script>";
        exit();
    }

    // Preparar la consulta SQL para eliminar el usuario
    $sql = "DELETE FROM login WHERE Id = ?";
    $stmt = $conex->prepare($sql);

    // Verificar si la preparación fue exitosa
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . htmlspecialchars($conex->error));
    }

    // Vincular parámetros y ejecutar
    $stmt->bind_param("i", $id);

    // Verificar si la ejecución fue exitosa
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Usuario eliminado con éxito.'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('No se encontró un usuario con ese ID.'); window.location.href='admin.php';</script>";
        }
    } else {
        echo "<script>alert('Error al eliminar el usuario: " . htmlspecialchars($stmt->error) . "');</script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('No se recibió ninguna solicitud para eliminar.'); window.location.href='admin.php';</script>";
}
