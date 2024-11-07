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

// Recibir los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $cedula = $_POST['cedula'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $telefono = $_POST['telefono'];
    $correo_electronico = $_POST['correo_electronico'];

    // Insertar datos en la tabla cliente
    $sql_cliente = "INSERT INTO cliente (Nombres, Apellidos, Cedula, Direccion, Ciudad, Telefono, Correo_electronico)
    VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_cliente = $conn->prepare($sql_cliente);
    $stmt_cliente->bind_param("sssssss", $nombres, $apellidos, $cedula, $direccion, $ciudad, $telefono, $correo_electronico);

    if ($stmt_cliente->execute()) {
        // Obtener el ID del cliente insertado
        $cliente_id = $stmt_cliente->insert_id;

        // Crear un usuario para este cliente
        $sql_usuario = "INSERT INTO usuarios (cliente_id, username, password, rol) VALUES (?, ?, ?, 'Usuario')";
        $password = password_hash('defaultpassword', PASSWORD_BCRYPT); // Cambiar 'defaultpassword' por una contraseña segura
        $stmt_usuario = $conn->prepare($sql_usuario);
        $stmt_usuario->bind_param("iss", $cliente_id, $correo_electronico, $password);

        if ($stmt_usuario->execute()) {
            echo "Nuevo registro creado exitosamente";
        } else {
            echo "Error: " . $sql_usuario . "<br>" . $conn->error;
        }

        $stmt_usuario->close();
    } else {
        echo "Error: " . $sql_cliente . "<br>" . $conn->error;
    }

    $stmt_cliente->close();
    $conn->close();
}
?>