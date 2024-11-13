<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <!-- Incluyendo el archivo de estilos del administrador -->
    <link rel="stylesheet" href="path/to/your/styles.css">
    <style>
        /* Aquí están los estilos personalizados para el administrador */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #2c3e50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        .tab-container {
            padding: 20px;
        }

        .list-container {
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        .product-table, .service-table {
            margin-bottom: 30px;
        }

        .btn {
            background-color: #3498db;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px 0;
        }

        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Panel de Administración</h1>
    </header>

    <div class="tab-container">
        <!-- Aquí se mostrarán las pestañas con enlaces a cada sección -->
        <nav>
            <a href="?seccion=servicios" class="btn">Servicios</a>
            <a href="?seccion=productos" class="btn">Productos</a>
            <a href="?seccion=facturas" class="btn">Facturas</a>
            <a href="?seccion=devoluciones" class="btn">Devoluciones</a>
        </nav>

        <div class="list-container">
            <!-- Incluir las secciones dinámicamente según la pestaña seleccionada -->
            <?php
                if (isset($_GET['seccion'])) {
                    $seccion = $_GET['seccion'];
                    switch ($seccion) {
                        case 'servicios':
                            include 'secciones/servicios.php';
                            break;
                        case 'productos':
                            include 'secciones/productos.php';
                            break;
                        case 'facturas':
                            include 'secciones/facturas.php';
                            break;
                        case 'devoluciones':
                            include 'secciones/devoluciones.php';
                            break;
                        default:
                            echo "<p>Seleccione una sección válida.</p>";
                    }
                } else {
                    echo "<p>Bienvenido al panel de administración.</p>";
                }
            ?>
        </div>
    </div>

    <!-- Incluyendo el archivo de scripts -->
    <script src="path/to/your/scripts.js"></script>
    <script>
        // Aquí se pueden añadir scripts personalizados, como confirmaciones de acciones
        function confirmarEliminacion() {
            return confirm('¿Estás seguro de que deseas eliminar este elemento?');
        }
    </script>
</body>
</html>