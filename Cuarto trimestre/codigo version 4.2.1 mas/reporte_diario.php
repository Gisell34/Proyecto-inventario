<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario de Facturas</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            padding: 10px;
            color: white;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 300px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            color: #333;
        }

        form input[type="date"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            padding: 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Estilos para la tabla de resultados */
        .table-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 10px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #007bff;
            color: white;
        }

        table tr:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }

        /* Estilos para el modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <!-- Enlace para regresar a la sección de facturas dentro del panel de administración -->
            <a href="admin.php">Regresar</a>
        </nav>
    </header>
    <h1>Reporte Diario de Facturas</h1>

    <form action="" method="post">
        <label for="fecha">Seleccionar Fecha:</label>
        <input type="date" id="fecha" name="fecha" required>
        <input type="submit" value="Filtrar">
    </form>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        include 'database.php';

        $fecha_seleccionada = $_POST['fecha'];

        $sql = "SELECT * FROM facturas WHERE Fecha = '$fecha_seleccionada'";
        $result = $conn->query($sql);

        if ($result === false) {
            $message = "Error en la consulta: " . $conn->error;
        } else {
            if ($result->num_rows > 0) {
                echo "<div class='table-container'>";
                echo "<h2>Facturas Registradas el $fecha_seleccionada</h2>";
                echo "<table>";
                echo "<tr><th>ID Factura</th><th>Fecha</th><th>Nombre Cliente</th><th>Productos</th><th>Cantidad</th><th>Precio</th><th>Servicio</th><th>Tipo</th><th>Precio</th><th>Total</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_Factura"] . "</td>";
                    echo "<td>" . $row["Fecha"] . "</td>";
                    echo "<td>" . $row["Nombre_cliente"] . "</td>";
                    echo "<td>" . $row["Productos"] . "</td>";
                    echo "<td>" . $row["Cantidad"] . "</td>";
                    echo "<td>" . $row["Precio"] . "</td>";
                    echo "<td>" . $row["Nombre_servicio"] . "</td>";
                    echo "<td>" . $row["Tipo_servicio"] . "</td>";
                    echo "<td>" . $row["Precio_venta"] . "</td>";
                    echo "<td>" . $row["Total"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                echo "</div>";
            } else {
                $message = "No se encontraron facturas registradas el $fecha_seleccionada";
            }
        }

        $conn->close();

        if (isset($message)) {
            echo "<script>
                var modal = document.getElementById('myModal');
                var span = document.getElementsByClassName('close')[0];
                var message = '$message';
                document.getElementById('modal-message').innerText = message;
                modal.style.display = 'block';
                span.onclick = function() {
                    modal.style.display = 'none';
                }
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }
            </script>";
        }
    }
    ?>
</body>

</html>