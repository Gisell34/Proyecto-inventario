<?php
include('conexion.php');

// Obtener los datos del formulario
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';
$rol = $_POST['rol'] ?? 0;

// Verificar si hay algún Id en la tabla login
$sql_verificar_Id = $conex->prepare("SELECT Id FROM login ORDER BY Id DESC LIMIT 1");
$sql_verificar_Id->execute();
$sql_verificar_Id->bind_result($Id);
$sql_verificar_Id->fetch();
$sql_verificar_Id->close();

// Si no hay Id en la tabla, iniciar el Id desde 1
$Id = $Id ? $Id + 1 : 1;

// Preparar la consulta SQL para insertar el nuevo usuario
$sql_login = $conex->prepare("INSERT INTO login (Id, usuario, password, rol) VALUES (?, ?, ?, ?)");

if ($sql_login) {
    $conex->begin_transaction();
    try {
        // Vincular los parámetros y ejecutar la consulta
        $sql_login->bind_param("isis", $Id, $usuario, $password, $rol);
        if ($sql_login->execute()) {
            echo '<div class="success-message">Usuario guardado exitosamente.</div>';
        } else {
            throw new Exception("Error al insertar en la tabla 'login': " . $sql_login->error);
        }

        // Confirmar la transacción
        $conex->commit();
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conex->rollback();
        echo '<div class="error-message">Error al guardar el usuario: ' . $e->getMessage() . '</div>';
    }

    // Cerrar la consulta
    $sql_login->close();
} else {
    echo '<div class="error-message">Error al preparar la consulta: ' . $conex->error . '</div>';
}

// Cerrar la conexión
$conex->close();
?>

<a href="admin.php">Volver al módulo administrador</a>