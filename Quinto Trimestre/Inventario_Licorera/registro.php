<?php
session_start();

include('conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    $errores = [];

    if (!preg_match("/^[a-zA-Z\s]+$/", $nombres)) {
        $errores[] = "Los Nombres deben contener solo letras y espacios.";
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $apellidos)) {
        $errores[] = "Los Apellidos deben contener solo letras y espacios.";
    }

    if (!preg_match("/^\d+$/", $cedula)) {
        $errores[] = "La Cédula debe ser un número.";
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $ciudad)) {
        $errores[] = "La Ciudad debe contener solo letras y espacios.";
    }

    if (!preg_match("/^\d+$/", $telefono)) {
        $errores[] = "El Teléfono debe ser un número.";
    }

    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Correo electrónico inválido.";
    }

    if (empty($nombres) || empty($apellidos) || empty($cedula) || empty($direccion) || empty($ciudad) || empty($telefono) || empty($correo_electronico) || empty($usuario) || empty($clave) || empty($rol)) {
        $errores[] = "Por favor, complete todos los campos obligatorios.";
    }

    if (strlen($clave) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    $roles_validos = ['Usuario', 'Administrador'];
    if (!in_array($rol, $roles_validos)) {
        $errores[] = "Rol inválido seleccionado.";
    }

    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo "<script>alert('$error'); window.history.back();</script>";
        }
        exit();
    }

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

    $password_hash = password_hash($clave, PASSWORD_DEFAULT);

    $conex->begin_transaction();

    try {
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

        $stmt_cliente = $conex->prepare("INSERT INTO cliente (Nombres, Apellidos, Cedula, Direccion, Ciudad, Telefono, Correo_electronico, CodigoCliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt_cliente) {
            throw new Exception("Error al preparar la consulta de cliente: " . $conex->error);
        }
        $stmt_cliente->bind_param("ssissisi", $nombres, $apellidos, $cedula, $direccion, $ciudad, $telefono, $correo_electronico, $usuario_id);
        if (!$stmt_cliente->execute()) {
            throw new Exception("Error al insertar en cliente: " . $stmt_cliente->error);
        }
        $stmt_cliente->close();

        $conex->commit();

        echo "<script>alert('Registro completado exitosamente. Puedes iniciar sesión ahora.'); window.location.href = 'login.php';</script>";
    } catch (Exception $e) {
        $conex->rollback();
        echo "<script>alert('Hubo un problema durante el registro: " . $e->getMessage() . "'); window.history.back();</script>";
    }

    $conex->close();
} else {
    header("Location: registro.html");
    exit();
}
?>