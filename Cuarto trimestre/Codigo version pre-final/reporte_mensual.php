<?php
// Incluye el archivo de conexión a la base de datos
include 'database.php';  // Asegúrate de tener este archivo para conectar con la base de datos

// Inicializar variables para los datos
$monthly_sales = array_fill(0, 12, 0);  // Ventas mensuales inicializadas a 0
$monthly_percentages = array_fill(0, 12, 0);  // Porcentajes inicializados a 0
$year = date('Y'); // Año por defecto
$start_date = '';
$end_date = '';

// Procesar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el año y rango de fechas del formulario
    $year = intval($_POST['year']);
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

    // Sanitizar y validar el año ingresado
    if ($year < 2000 || $year > 2100) {
        echo "<div class='alert alert-danger'>Año inválido. Por favor, ingresa un año entre 2000 y 2100.</div>";
        exit;
    }

    // Consulta para obtener las ventas mensuales del año seleccionado
    $sql = "SELECT MONTH(Fecha) AS mes, SUM(Total) AS total_ventas
            FROM facturas
            WHERE YEAR(Fecha) = ? AND Fecha BETWEEN ? AND ?
            GROUP BY MONTH(Fecha)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $year, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Procesar resultados de la consulta
    while ($row = $result->fetch_assoc()) {
        $mes = intval($row['mes']) - 1;  // Convertir a índice 0-based
        if ($mes >= 0 && $mes < 12) {
            $monthly_sales[$mes] = floatval($row['total_ventas']);
        }
    }

    $stmt->close();
    $conn->close();

    // Calcular el total anual de ventas
    $total_annual_sales = array_sum($monthly_sales);

    // Convertir las ventas mensuales a porcentajes
    if ($total_annual_sales > 0) {
        $monthly_percentages = array_map(function ($value) use ($total_annual_sales) {
            return round(($value / $total_annual_sales) * 100, 2);
        }, $monthly_sales);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Mensual de Ventas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    margin: 0;
    padding: 0;
}

header {
    background: linear-gradient(to right, #7bc0c5, #9495b9);
    padding: 10px;
    color: white;
    text-align: center;
}

h1 {
    text-align: center;
    margin: 20px 0;
}

.container {
    max-width: 600px;
    margin: 0 auto;
}

.chart-container {
    margin-top: 30px;
    max-width: 600px; /* Ajustar el tamaño del gráfico */
    margin: 0 auto; /* Centrar el gráfico */
}

.table-container {
    margin-top: 20px;
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    margin-top: 10px;
}

table th,
table td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background: linear-gradient(to right, #7bc0c5, #9495b9);
    color: black;
}

.btn-custom {
    background: linear-gradient(to right, #7bc0c5, #9495b9);
    color: black;
    padding: 10px 20px; /* Añadir un poco de relleno para mayor tamaño */
    border: none; /* Sin borde */
    border-radius: 5px; /* Bordes redondeados */
    text-align: center; /* Alinear el texto al centro */
    text-decoration: none; /* Sin subrayado para enlaces */
    display: inline-block; /* Para que se comporten como botones */
}

.back-link-container {
    display: flex;
    justify-content: center;
    margin: 20px 0;
}

.back-link-container .btn {
    margin: 0 5px; /* Espaciado entre los botones */
}
    </style>
</head>

<body>
    <header>
        <h1>Reporte Mensual de Ventas</h1>
    </header>

    <div class="container">
    <div class="text-center mb-3">


<form action="" method="post" class="text-center">
    <label for="year" class="form-label">Seleccionar Año:</label>
    <input type="number" id="year" name="year" value="<?= htmlspecialchars($year); ?>" class="form-control" required>
    
    <label for="start_date" class="form-label mt-2">Fecha de Inicio:</label>
    <input type="date" id="start_date" name="start_date" value="<?= htmlspecialchars($start_date); ?>" class="form-control" required>
    
    <label for="end_date" class="form-label mt-2">Fecha de Fin:</label>
    <input type="date" id="end_date" name="end_date" value="<?= htmlspecialchars($end_date); ?>" class="form-control" required>
    
    <div class="back-link-container mt-3">
        <input type="submit" value="Generar Reporte" class="btn btn-custom">
        <a href="lista-facturas.php" class="btn btn-custom">Regresar a Lista de Facturas</a>
    </div>
</form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
            <div class="chart-container">
                <canvas id="ventasChart"></canvas>
            </div>

            <div class="table-container">
                <h2 class='text-center'>Ventas Mensuales en <?= htmlspecialchars($year); ?></h2>
                <table class='table table-bordered'>
                    <thead>
                        <tr>
                            <th>Mes</th>
                            <th>Total Ventas</th>
                            <th>Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                        for ($i = 0; $i < 12; $i++) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($months[$i]) . "</td>";
                            echo "<td>$" . number_format($monthly_sales[$i], 2) . "</td>";
                            echo "<td>" . $monthly_percentages[$i] . "%</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <script>
            // Obtener contexto del canvas para la gráfica
            const ctx = document.getElementById('ventasChart').getContext('2d');

            // Crear un gradiente de color para la línea
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(255, 99, 132, 1)');
            gradient.addColorStop(1, 'rgba(255, 159, 64, 0.2)');

            // Datos para la gráfica
            const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Porcentaje de Ventas (%)',
                    data: <?= json_encode($monthly_percentages); ?>,
                    fill: false,
                    borderColor: gradient,
                    backgroundColor: gradient,
                    tension: 0.4, // Curvatura de la línea
                    pointBackgroundColor: 'rgba(255, 99, 132, 1)', // Color de los puntos
                    pointBorderColor: '#fff',
                    pointHoverRadius: 8,
                    pointRadius: 5,
                    borderWidth: 2
                }]
            };

            // Configuración de la gráfica
            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + '%'; // Mostrar % en el eje Y
                                }
                            }
                        }
                    }
                }
            };

            // Renderizar la gráfica
            new Chart(ctx, config);
        </script>
    <?php endif; ?>
</body>

</html>