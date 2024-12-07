<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Servicios Disponibles</title>
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
        <h1>Servicios Disponibles</h1>
    </header>

    <div class="user-panel">
        <div class="list-container">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Código de Servicio</th>
                        <th>Nombre del Servicio</th>
                        <th>Tipo de Servicio</th>
                        <th>Precio de Venta</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT Codigo, Nombre_servicio, Tipo_servicio, Precio_venta FROM servicios";
                    if ($stmt = $conex->prepare($sql)) {
                        $stmt->execute();
                        $resultado = $stmt->get_result();

                        if ($resultado->num_rows > 0) {
                            while ($row = $resultado->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['Codigo']}</td>
                                        <td>{$row['Nombre_servicio']}</td>
                                        <td>{$row['Tipo_servicio']}</td>
                                        <td>{$row['Precio_venta']}</td>
                                        <td>
                                            <form method='POST' action='agregar_servicio_carrito.php'>
                                                <input type='hidden' name='codigo' value='{$row['Codigo']}'>
                                                <button type='submit'>Agregar al Carrito</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No hay servicios disponibles</td></tr>";
                        }
                        $stmt->close();
                    } else {
                        echo "<tr><td colspan='5'>Error en la consulta: " . $conex->error . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a class="regresar" href="user.php">Regresar</a>
    </div>
</body>
</html>