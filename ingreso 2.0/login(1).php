<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>ingreso a la Plataforma</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	<div class="caja1">
		<form method="POST" action="login.php">
			<div class="formtlo">Iniciar sesión</div>
			<div class="ub1">&#128273; Ingresar usuario</div>
			<input type="text" name="usuario">
			<div class="ub1">&#128274; Ingresar password</div>

			<input type="password" name="password" id="password">

			<div class="ub1">
				<input type="checkbox" onclick="verpassword()">Mostrar contraseña
			</div>
			<div class="ub1">Rol</div>
			<select name="rol">
				<option value="0" style="display:none;"><label>Seleccionar</label></option>
				<option value="Docente">Docente</option>
				<option value="Administrador">Administrador</option>
				<option value="Estudiante">Estudiante</option>
			</select>

			<div align="center">
				<input type="submit" value="Ingresar">

				<input type="reset" value="Cancelar">
			</div>
		</form>
	</div>
</body>
<script>
	function verpassword() {
		var tipo = document.getElementById("password");
		if (tipo.type == "password") {
			tipo.type = "text";
		} else {
			tipo.type = "password";
		}
	}
</script>

</html>
<?php

include('conexion.php');

$usu = $_POST['usuario'];
$pass = $_POST['password'];
$rol = $_POST['rol'];

$queryusuario = mysqli_query($conex, "SELECT * FROM login WHERE usuario ='$usu' and password = '$pass' and rol = '$rol'");
$nr 		= mysqli_num_rows($queryusuario);

if ($nr == 1) {
	if ($rol == "Docente") {
		header("Location: docente.php");
	} else if ($rol == "Administrador") {
		header("Location: admin.php");
	} else if ($rol == "Estudiante") {
		header("Location: estudiante.php");
	}
} else {
	echo "<script> alert('Usuario, contraseña o rol incorrecto.');window.location= 'login.php' </script>";
}
?>