<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Administrador") {
    header("Location: login.php");
    exit();
}
include('conexion.php');

$usuario = $_SESSION['usuario'];

$consulta = "SELECT Usuario FROM usuarios WHERE Usuario = ?";
if ($stmt = $conex->prepare($consulta)) {
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($row = $resultado->fetch_assoc()) {
        $nombre_administrador = $row['Usuario'];
    } else {
        $nombre_administrador = "Administrador";
    }
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta SQL: " . $conex->error;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Admin - Panel de administración</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
            font-weight: bold;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 10px 0;
            text-align: center;
            color: white;
        }

        nav {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: black;
            text-decoration: none;
            padding: 20px 20px;
            border-radius: 8px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            transition: background-color 0.1s ease;
        }

        nav a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .user-panel {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .user-panel h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .user-panel a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .user-panel a:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-table th,
        .user-table td {
            padding: 10px;
            text-align: center;
            border: 3px double #7bc0c5;
            vertical-align: middle;
        }

        .user-table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
        }

        .user-table td a {
            display: inline-block;
            padding: 5px 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 5px;
        }

        .user-table td a:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="lista-usuarios.php">Usuarios registrados</a>
            <a href="lista-proveedores.php">Proveedores registrados</a>
            <a href="lista-servicios.php">Servicios registrados</a>
            <a href="lista-productos.php">Inventario</a>
            <a href="lista-facturas.php">Lista de facturas</a>
            <a href="formulario_devoluciones.php">Devoluciones</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="user-panel">
        <h1>Bienvenido, <?php echo htmlspecialchars($nombre_administrador); ?>!</h1>
        <div class="user-section">
        
            <div id="usuarios" style="display: none;">
                <?php include('lista-usuarios.php'); ?> 
            </div>

            <div id="proveedores" style="display: none;">
                <?php include('lista-proveedores.php'); ?> 
            </div>

            <div id="servicios" style="display: none;">
                <?php include('lista-servicios.php'); ?> 
            </div>

            <div id="productos" style="display: none;">
                <?php include('lista-productos.php'); ?> 
            </div>
           
            <div id="facturas" style="display: none;">
                <?php include('lista-facturas.php'); ?> 
            </div>

            <div id="devoluciones" style="display: none;">
                <h2>Formulario de Devoluciones</h2>
                <a href="#" onclick="ocultarSecciones();">Regresar</a>
                <a href="formulario_devoluciones.php" class="button">Formulario de Devolución</a>
            </div>
        </div>
    </div>

    <script>
        function mostrarUsuarios() {
            ocultarSecciones();
            document.getElementById("usuarios").style.display = "block";
        }

        function mostrarProveedores() {
            ocultarSecciones();
            document.getElementById("proveedores").style.display = "block";
        }

        function mostrarServicios() {
            ocultarSecciones();
            document.getElementById("servicios").style.display = "block";
        }

        function mostrarProductos() {
            ocultarSecciones();
            document.getElementById("productos").style.display = "block";
        }

        function mostrarFacturas() {
            ocultarSecciones();
            document.getElementById("facturas").style.display = "block";
        }

        function ocultarSecciones() {
            document.getElementById("usuarios").style.display = "none";
            document.getElementById("proveedores").style.display = "none";
            document.getElementById("servicios").style.display = "none";
            document.getElementById("productos").style.display = "none";
            document.getElementById("facturas").style.display = "none";
            document.getElementById("devoluciones").style.display = "none";
        }
    </script>
</body>
</html>