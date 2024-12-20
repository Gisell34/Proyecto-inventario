<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$sql = "SELECT Codigoproducto, Nombre_producto, Fabricante, Tipo_producto, Especificaciones FROM producto";
$resultado = $conex->query($sql);

if ($resultado === false) {
    die("Error en la consulta SQL: " . $conex->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lista de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 10px 0;
            text-align: center;
            color: white;
        }

        .user-panel {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .user-panel h1 {
            margin-bottom: 20px;
        }

        .list-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
        }

        .user-table th,
        .user-table td {
            padding: 10px;
            text-align: center;
            border: 3px double #9bbec0;
        }

        .user-table th {
            background-color: #9bbec0;
            color: white;
        }

        .user-table td form {
            display: inline;
        }

        .user-table td button {
            padding: 5px 10px;
            background-color: #9bbec0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .user-table td button:hover {
            background-color: #899da4;
        }

        .regresar {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #9bbec0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .regresar:hover {
            background-color: #899da4;
        }
    </style>
</head>
<body>
    <header>
        <h1>Lista de Productos</h1>
    </header>

    <div class="user-panel">
        <div class="list-container">
            <?php
            if ($resultado->num_rows > 0) {
                echo "<table class='user-table'>
                        <thead>
                            <tr>
                                <th>Código de Producto</th>
                                <th>Nombre del Producto</th>
                                <th>Fabricante</th>
                                <th>Tipo de Producto</th>
                                <th>Especificaciones</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = $resultado->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['Codigoproducto']}</td>
                            <td>{$row['Nombre_producto']}</td>
                            <td>{$row['Fabricante']}</td>
                            <td>{$row['Tipo_producto']}</td>
                            <td>{$row['Especificaciones']}</td>
                            <td>
                                <form method='POST' action='agregar_producto_carrito.php'>
                                    <input type='hidden' name='codigo' value='{$row['Codigoproducto']}'>
                                    <button type='submit'>Agregar al Carrito</button>
                                </form>
                            </td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "No se encontraron productos.";
            }
            ?>
        </div>

        <a class="regresar" href="user.php">Regresar</a>
    </div>
</body>
</html>