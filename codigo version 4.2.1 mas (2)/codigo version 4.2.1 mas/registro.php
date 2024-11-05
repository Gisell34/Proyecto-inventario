<?php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "inventariolicorera";

// Crear la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Verificar si el formulario ha sido enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y sanitizar datos del formulario
    $nombres = trim($_POST["Nombres"] ?? '');
    $apellidos = trim($_POST["Apellidos"] ?? '');
    $cedula = trim($_POST["Cedula"] ?? '');
    $direccion = trim($_POST["Direccion"] ?? '');
    $ciudad = trim($_POST["Ciudad"] ?? '');
    $telefono = trim($_POST["Telefono"] ?? '');
    $correo_electronico = trim($_POST["Correo_electronico"] ?? '');
    $usuario = trim($_POST["usuario"] ?? '');
    $clave = trim($_POST["clave"] ?? '');
    $rol = trim($_POST["rol"] ?? '');
    
    // Validar datos
    if (!preg_match("/^[a-zA-Z\s]+$/", $nombres)) {
        die("Los Nombres deben contener solo letras y espacios.");
    }
    
    if (!preg_match("/^[a-zA-Z\s]+$/", $apellidos)) {
        die("Los Apellidos deben contener solo letras y espacios.");
    }

    if (!preg_match("/^\d+$/", $cedula)) {
        die("La Cédula debe ser un número.");
    }

    if (!preg_match("/^[a-zA-Z\s]+$/", $ciudad)) {
        die("La Ciudad debe contener solo letras y espacios.");
    }
    
    if (!preg_match("/^\d+$/", $telefono)) {
        die("El Teléfono debe ser un número.");
    }

    // Validar correo electrónico
    if (!filter_var($correo_electronico, FILTER_VALIDATE_EMAIL)) {
        die("Correo electrónico inválido.");
    }

    // Verificar si algún campo obligatorio está vacío
    if (!$nombres || !$apellidos || !$cedula || !$direccion || !$ciudad || !$telefono || !$correo_electronico || !$usuario || !$clave) {
        die("Por favor, complete todos los campos obligatorios.");
    }

    // Hash de la contraseña si está presente
    $password_hash = password_hash($clave, PASSWORD_DEFAULT);

    // Iniciar transacción
    $conn->begin_transaction();

    // Insertar datos en la tabla usuarios
    $sql_usuario = "INSERT INTO usuarios (Usuario, Password, rol) VALUES (?, ?, ?)";
    $stmt_usuario = $conn->prepare($sql_usuario);

    if ($stmt_usuario) {
        $stmt_usuario->bind_param("sss", $usuario, $password_hash, $rol);

        if ($stmt_usuario->execute()) {
            // Obtener el ID del usuario insertado
            $usuario_id = $stmt_usuario->insert_id;

            // Insertar datos en la tabla cliente
            $sql_cliente = "INSERT INTO cliente (Nombres, Apellidos, Cedula, Direccion, Ciudad, Telefono, Correo_electronico, CodigoCliente) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_cliente = $conn->prepare($sql_cliente);

            if ($stmt_cliente) {
                $stmt_cliente->bind_param("ssissisi", $nombres, $apellidos, $cedula, $direccion, $ciudad, $telefono, $correo_electronico, $usuario_id);

                if ($stmt_cliente->execute()) {
                    // Confirmar la transacción
                    $conn->commit();
                    echo "Nuevo registro creado exitosamente";
                } else {
                    // Revertir la transacción en caso de error
                    $conn->rollback();
                    echo "Error al insertar cliente: " . $stmt_cliente->error;
                }

                $stmt_cliente->close();
            } else {
                echo "Error al preparar la sentencia para insertar en la tabla cliente: " . $conn->error;
            }
        } else {
            echo "Error al insertar usuario: " . $stmt_usuario->error;
        }

        $stmt_usuario->close();
    } else {
        echo "Error al preparar la sentencia para insertar en la tabla usuarios: " . $conn->error;
    }

    $conn->close();
}
?>
<a href="login.php">Volver al inicio</a>