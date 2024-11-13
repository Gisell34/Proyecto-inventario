<?php
include('conexion.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$productosPorPagina = 3;
$paginaActual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$inicio = ($paginaActual - 1) * $productosPorPagina;

$sql_productos_inventario = "SELECT p.Codigoproducto, p.Nombre_producto, p.Fabricante, p.Tipo_producto, p.Especificaciones,
                                    i.Cantidad_producto, i.Precio, i.precio_venta, i.Entrada, i.Salida, i.stock_minimo, i.stock_maximo
                            FROM producto p
                            LEFT JOIN inventario i ON p.Codigoproducto = i.Codigoproducto
                            LIMIT $inicio, $productosPorPagina";
$resultado_productos_inventario = $conex->query($sql_productos_inventario);

$sqlTotalproductos = "SELECT COUNT(*) as total FROM producto";
$resultadoTotal = $conex->query($sqlTotalproductos);
$totalproductos = $resultadoTotal->fetch_assoc()['total'];
$totalPaginas = ceil($totalproductos / $productosPorPagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de productos</title>
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
            padding: 20px 0;
            text-align: center;
            color: black;
        }

        h1 {
            color: #444552;
            margin: 0;
        }

        .user-panel-productos {
            max-width: 1800px;
            margin: 10px auto;
            padding: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ccc;
        }

        table th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a,
        .button {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s;
            text-align: center;
        }

        .pagination a:hover {
            background-color: #9495b9;
        }

        .pagination a.active {
            background-color: #6ab3b0;
            color: #0e0b0b;
            border: 1px solid #6ab3b0;
        }

        .search-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }

        .search-container button {
            padding: 10px 15px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .search-container button:hover {
            background-color: #9495b9;
        }

        form {
            margin-bottom: 20px;
        }

        .back-link-container {
            text-align: center;
        }

        .add-button {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <header>
        <h1>Lista de productos en inventario</h1>
    </header>

    <div class="user-panel-productos">
        <div class="back-link-container">
            <a href="admin.php" class="button">Regresar al Administrador</a>
            <a href="formulario.html" class="button">Agregar producto</a>
        </div>

        <div class="search-container">
            <input type="text" id="buscarProducto" onkeyup="buscarProducto()" placeholder="Buscar producto...">
            <button onclick="buscarProducto()">Buscar</button>
        </div>

        <div style="overflow-x: auto; margin: 10px 0;">
            <table class="productos-table" style="margin: 0 auto; border-collapse: collapse; width: 100%;">
                <thead>
                    <tr style="text-align: left;">
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Fabricante</th>
                        <th>Tipo</th>
                        <th>Especificaciones</th>
                        <th>Cantidad</th>
                        <th>Precio de Compra</th>
                        <th>Precio de Venta</th>
                        <th>Ganancia</th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Stock Mínimo</th>
                        <th>Stock Máximo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultado_productos_inventario->fetch_assoc()) :
                        $precio_compra = $row["Precio"];
                        $precio_venta = $row["precio_venta"];
                        $ganancia = $precio_venta - $precio_compra;
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($row["Codigoproducto"]) ?></td>
                            <td><?= htmlspecialchars($row["Nombre_producto"]) ?></td>
                            <td><?= htmlspecialchars($row["Fabricante"]) ?></td>
                            <td><?= htmlspecialchars($row["Tipo_producto"]) ?></td>
                            <td><?= htmlspecialchars($row["Especificaciones"]) ?></td>
                            <td><?= htmlspecialchars($row["Cantidad_producto"]) ?></td>
                            <td><?= number_format($precio_compra, 2) ?></td>
                            <td><?= number_format($precio_venta, 2) ?></td>
                            <td><?= number_format($ganancia, 2) ?></td>
                            <td><?= htmlspecialchars($row["Entrada"]) ?></td>
                            <td><?= htmlspecialchars($row["Salida"]) ?></td>
                            <td><?= htmlspecialchars($row["stock_minimo"]) ?></td>
                            <td><?= htmlspecialchars($row["stock_maximo"]) ?></td>
                            <td>
                                <a href="modificar_producto.php?codigo=<?= htmlspecialchars($row['Codigoproducto']) ?>" class="button">Editar</a>
                                <a href="eliminar_producto.php?codigo=<?= htmlspecialchars($row['Codigoproducto']) ?>" class="button" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <?php if ($paginaActual > 1): ?>
                <a href="lista-productos.php?pagina=<?= ($paginaActual - 1) ?>">Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="lista-productos.php?pagina=<?= $i ?>" class="<?= ($i == $paginaActual) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($paginaActual < $totalPaginas): ?>
                <a href="lista-productos.php?pagina=<?= ($paginaActual + 1) ?>">Siguiente</a>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function buscarProducto() {
            var input = document.getElementById("buscarProducto").value.toLowerCase();
            var table = document.querySelector(".productos-table tbody");
            var rows = table.getElementsByTagName("tr");

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var encontrado = false;

                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().indexOf(input) > -1) {
                        encontrado = true;
                        break;
                    }
                }

                if (encontrado) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>