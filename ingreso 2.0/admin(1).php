<?php
include('conexion.php');

// Inicializar variables
$usuario = $rol = '';
$id = '';

// Verificar si se ha enviado el formulario para buscar el usuario por ID
if (isset($_GET['id'])) {
	$id = $_GET['id'];

	// Consultar la información del usuario con el ID proporcionado
	$sql = "SELECT usuario, rol FROM login WHERE Id = ?";
	$stmt = $conex->prepare($sql);
	$stmt->bind_param("i", $id);
	$stmt->execute();
	$stmt->bind_result($usuario, $rol);

	// Verificar si se encontró un usuario
	if (!$stmt->fetch()) {
		echo "<script>alert('Usuario no encontrado.');</script>";
		$usuario = $rol = '';
	}

	$stmt->close();
}

// Verificar si se ha enviado el formulario para modificar la información
if (isset($_POST['modificar'])) {
	$id = $_POST['id'];
	$usuario = $_POST['usuario'];
	$rol = $_POST['rol'];

	// Actualizar la información del usuario en la base de datos
	$sql = "UPDATE login SET usuario = ?, rol = ? WHERE Id = ?";
	$stmt = $conex->prepare($sql);
	$stmt->bind_param("ssi", $usuario, $rol, $id);

	if ($stmt->execute()) {
		echo "<script>alert('Usuario modificado con éxito.'); window.location.href='admin.php';</script>";
	} else {
		echo "<script>alert('Error al modificar el usuario.');</script>";
	}

	$stmt->close();
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin - Gestión de Usuarios</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	<h1>Gestión de Usuarios</h1>

	<h2>Eliminar Usuario</h2>
	<form method="post" action="eliminar.php">
		<label for="Id">ID del Usuario:</label>
		<input type="text" id="Id" name="Id" required>
		<input type="submit" name="eliminar" value="Eliminar">
	</form>

	<h2>Modificar Usuario</h2>
	<form method="get" action="">
		<label for="id">ID del Usuario:</label>
		<input type="text" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>" required>
		<input type="submit" value="Buscar">
	</form>

	<!-- Formulario para modificar los datos del usuario -->
	<form method="post" action="">
		<label for="usuario">Nombre de Usuario:</label>
		<input type="text" id="usuario" name="usuario" value="<?php echo htmlspecialchars($usuario); ?>" required>
		<br>

		<label for="rol">Rol:</label>
		<select id="rol" name="rol" required>
			<option value="admin" <?php if ($rol == 'admin') echo 'selected'; ?>>Admin</option>
			<option value="docente" <?php if ($rol == 'docente') echo 'selected'; ?>>Docente</option>
			<option value="estudiante" <?php if ($rol == 'estudiante') echo 'selected'; ?>>Estudiante</option>
		</select>
		<br>

		<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
		<input type="submit" name="modificar" value="Modificar">
	</form>
	<h2>Agregar Nuevo Usuario</h2>
	<form action="guardar.php" method="post" class="guardar-form">
		<label for="nombre">Nombre:</label>
		<input type="text" id="nombre" name="usuario" required>
		<br>

		<label for="password">Contraseña:</label>
		<input type="password" id="contrasena" name="password" required>
		<br>

		<label for="rol">Rol:</label>
		<select id="rol" name="rol" required>
			<option value="admin">Admin</option>
			<option value="docente">Docente</option>
			<option value="estudiante">Estudiante</option>
		</select>
		<br>

		<input type="submit" name="agregar" value="Agregar">
	</form>
</body>

</html>