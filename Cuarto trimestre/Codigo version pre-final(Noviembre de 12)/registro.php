<?php
// registro.php

// Iniciar la sesión
session_start();

// Incluir el archivo de conexión a la base de datos
include('conexion.php');

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener y sanitizar los datos del formulario
    $nombres = trim($_POST['Nombres'] ?? '');
    $apellidos = trim($_POST['Apellidos'] ?? '');
    $cedula = trim($_POST['Cedula'] ?? '');
    $direccion = trim($_POST['Direccion'] ?? '');
    $ciudad = trim($_POST['Ciudad'] ?? '');
    $telefono = trim($_POST['Telefono'] ?? '');
    $correo_electronico = trim($_POST['Correo_electronico'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = trim($_POST['clave'] ?? '');
    $rol = trim($_POST['rol'] ?? '');

    // Validaciones de seguridad
    $errores = [];

    // Validar que los nombres solo contengan letras y espacios
    if (!preg_match("/^[a-zA-Z\s]+$/", $nombres)) {
        $errores[] = "Los Nombres deben contener solo letras y espacios.";
    }

    // Validar que los apellidos solo contengan letras y espacios
    if (!preg_match("/^[a-zA-Z\s]+$/", $apellidos)) {
        $errores[] = "Los Apellidos deben contener solo letras y espacios.";
    }

    // Validar que la cédula solo contenga números
    if (!preg_match("/^\d+$/", $cedula)) {
        $errores[] = "La Cédula debe ser un número.";
    }

    // Validar que la ciudad solo contenga letras y espacios
    if (!preg_match("/^[a-zA-Z\s]+$/", $ciudad)) {
        $errores[] = "La Ciudad debe contener solo letras y espacios.";
    }

    // Validar que el teléfono solo contenga números
    if (!preg_match("/^\d+$/", $telefono)) {
        $errores[] = "El Teléfono debe ser un número.";
    }

    // Validar el correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }

    // Validar que los campos obligatorios no estén vacíos
    if (empty($nombres) || empty($apellidos) || empty($cedula) || empty($direccion) || empty($ciudad) || empty($telefono) || empty($correo_electronico) || empty($usuario) || empty($clave) || empty($rol)) {
        $errores[] = "Por favor, complete todos los campos obligatorios.";
    }

    // Validar la longitud de la contraseña
    if (strlen($clave) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Validar que el rol sea válido
    $roles_validos = ['Usuario', 'Administrador'];
    if (!in_array($rol, $roles_validos)) {
        $errores[] = "Rol inválido seleccionado.";
    }

    // Si hay errores, mostrarlos y detener el script
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<script>alert('$error'); window.history.back();</script>";
        }
        exit();
    }

    // Verificar si el nombre de usuario ya existe
    $stmt_check = $conex->prepare("SELECT Usuario FROM usuarios WHERE Usuario = ?");
    if ($stmt_check) {
        $stmt_check->bind_param("s", $usuario);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            echo "<script>alert('El nombre de usuario ya está en uso. Por favor, elige otro.'); window.history.back();</script>";
            $stmt_check->close();
            exit();
        }
        $stmt_check->close();
    } else {
        echo "<script>alert('Error al verificar el nombre de usuario: " . $conex->error . "'); window.history.back();</script>";
        exit();
    }

    // Encriptar la contraseña
    $password_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Iniciar una transacción
    $conex->begin_transaction();

    try {
        // Insertar en la tabla 'usuarios'
        $stmt_usuario = $conex->prepare("INSERT INTO usuarios (Usuario, Password, rol) VALUES (?, ?, ?)");
        if (!$stmt_usuario) {
            throw new Exception("Error al preparar la consulta de usuarios: " . $conex->error);
        }
        $stmt_usuario->bind_param("sss", $usuario, $password_hash, $rol);
        if (!$stmt_usuario->execute()) {
            throw new Exception("Error al insertar en usuarios: " . $stmt_usuario->error);
        }
        $usuario_id = $stmt_usuario->insert_id;
        $stmt_usuario->close();

        // Insertar en la tabla 'cliente'
        $stmt_cliente = $conex->prepare("INSERT INTO cliente (Nombres, Apellidos, Cedula, Direccion, Ciudad, Telefono, Correo_electronico, CodigoCliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt_cliente) {
            throw new Exception("Error al preparar la consulta de cliente: " . $conex->error);
        }
        $stmt_cliente->bind_param("ssissisi", $nombres, $apellidos, $cedula, $direccion, $ciudad, $telefono, $correo_electronico, $usuario_id);
        if (!$stmt_cliente->execute()) {
            throw new Exception("Error al insertar en cliente: " . $stmt_cliente->error);
        }
        $stmt_cliente->close();

        // Confirmar la transacción
        $conex->commit();

        // Redirigir al usuario con un mensaje de éxito
        echo "<script>alert('Registro completado exitosamente. Puedes iniciar sesión ahora.'); window.location.href = 'login.php';</script>";
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conex->rollback();
        echo "<script>alert('Hubo un problema durante el registro: " . $e->getMessage() . "'); window.history.back();</script>";
    }

    // Cerrar la conexión
    $conex->close();
} else {
    // Si el formulario no fue enviado, redirigir al registro
    header("Location: registro.html");
    exit();
}
?>