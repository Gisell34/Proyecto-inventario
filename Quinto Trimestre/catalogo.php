<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('conexion.php');
if ($conex->connect_error) {
    die("Error de conexión: " . $conex->connect_error);
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$servicios = isset($_SESSION['servicios']) ? $_SESSION['servicios'] : array();

$productos_por_pagina = 8;
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;
$offset = ($pagina_actual - 1) * $productos_por_pagina;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_carrito'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1;

    if ($tipo === 'producto') {
        $consulta = "SELECT p.Nombre_producto, i.precio_venta 
                     FROM producto p 
                     JOIN inventario i ON p.Codigoproducto = i.Codigoproducto 
                     WHERE p.Codigoproducto = ?";
    } elseif ($tipo === 'servicio') {
        $consulta = "SELECT Nombre_servicio, Precio_venta FROM servicios WHERE Codigo = ?";
    }

    if ($stmt = $conex->prepare($consulta)) {
        $stmt->bind_param("i", $codigo);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $row = $resultado->fetch_assoc();
        $stmt->close();

        if ($row) {
            $nombre = $row['Nombre_producto'] ?? $row['Nombre_servicio'];
            $precio = $row['precio_venta'] ?? $row['Precio_venta'];

            if (is_numeric($precio) && $precio > 0) {
                if ($tipo === 'producto') {
                    $carrito[$codigo] = [
                        'nombre' => $nombre,
                        'cantidad' => $cantidad,
                        'precio' => $precio,
                        'tipo' => 'producto'
                    ];
                } elseif ($tipo === 'servicio') {
                    $servicios[$codigo] = [
                        'nombre' => $nombre,
                        'precio' => $precio,
                        'tipo' => 'servicio'
                    ];
                }

                $_SESSION['carrito'] = $carrito;
                $_SESSION['servicios'] = $servicios;

                echo "<script>actualizarConteoCarrito();</script>";
            } else {
                echo "<script>alert('Error: Precio inválido.');</script>";
            }
        } else {
            echo "<script>alert('Error: No se encontró el artículo.');</script>";
        }
    } else {
        echo "Error en la preparación de la consulta SQL: " . $conex->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Catálogo</title>
    <style>
        body { 
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            font-weight: bold;
        }

        .catalogo-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .producto-cuadro {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .producto {
            border: 1px solid #ddd;
            padding: 20px;
            text-align: center;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .producto img {
            max-width: 100px;
            margin-bottom: 10px;
        }

        .producto h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .producto p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .producto button {
            padding: 10px 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .producto button:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }

        .titulo-seccion {
            margin-top: 30px;
            font-size: 24px;
            color: #333;
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
        }

        .paginacion {
            text-align: center;
            margin-top: 20px;
        }

        .paginacion a {
            padding: 10px 15px;
            margin: 0 5px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .paginacion a:hover {
            background-color: #899da4;
        }

        .catalogo-servicios {
    width: 100%;
    border-collapse: collapse;
        }

        .catalogo-servicios th,
        .catalogo-servicios td {
            padding: 10px;
            text-align: center;
            border: 3px double #9bbec0;
        }

        .catalogo-servicios th {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            font-weight: bold;
        }

        .catalogo-servicios td button {
            padding: 5px 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .catalogo-servicios td button:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }

        .nube-flotante {
            position: fixed;
            top: 10px;
            right: 10px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 16px;
        }

        .boton-container {
            margin-top: 20px;
            text-align: center;
        }

        .boton-container button {
            padding: 10px 20px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
            font-weight: bold;
        }

        .boton-container button:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }
    </style>
    <script>
        function actualizarConteoMensajeFlotante() {
        let conteoProductos = <?= count($carrito) ?>;
        let conteoServicios = <?= count($servicios) ?>;
        let totalConteo = conteoProductos + conteoServicios;
        
        document.querySelector('.mensaje-flotante').dataset.conteo = totalConteo;
        document.querySelector('.mensaje-flotante').innerText = 
        "Productos en el carrito: " + conteoProductos + " productos y " + conteoServicios + " servicios";
        }

        function regresarPagina() {
            window.history.back();
        }

        function irAlCarrito() {
            window.location.href = 'carrito.php';
        }
    </script>
</head>
<body>
<div class="catalogo-container">
<h1>Catálogo</h1>
<div class="titulo-seccion">Productos</div>

        <div class="boton-container">
            <button onclick="regresarPagina()">Regresar</button>
            <button onclick="irAlCarrito()">Ir al Carrito</button>
        </div>

        <div id="carrito-nube" class="nube-flotante">
        <?= count($carrito) ?> productos y <?= count($servicios) ?> servicios en el carrito
        </div>

        <div class="producto-cuadro">
            <?php
            $consulta_productos = "SELECT p.Codigoproducto, p.Nombre_producto, p.Tipo_producto, i.precio_venta 
                                   FROM producto p 
                                   JOIN inventario i ON p.Codigoproducto = i.Codigoproducto 
                                   LIMIT $productos_por_pagina OFFSET $offset";
            $result_productos = $conex->query($consulta_productos);
            while ($row = $result_productos->fetch_assoc()) {
                ?>
                <div class="producto">
                <img src="imagenes/<?= htmlspecialchars($row['Codigoproducto']) ?>.png" alt="Producto">
                    <h3><?= htmlspecialchars($row['Nombre_producto']) ?></h3>
                    <p>$<?= htmlspecialchars($row['precio_venta']) ?></p>
                    <form method="POST">
                        <input type="hidden" name="codigo" value="<?= $row['Codigoproducto'] ?>">
                        <input type="hidden" name="tipo" value="producto">
                        <button type="submit" name="agregar_carrito">Agregar al Carrito</button>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>

        <div class="paginacion">
            <?php
            $consulta_total = "SELECT COUNT(*) as total FROM producto";
            $resultado_total = $conex->query($consulta_total);
            $total_productos = $resultado_total->fetch_assoc()['total'];
            $total_paginas = ceil($total_productos / $productos_por_pagina);

            for ($i = 1; $i <= $total_paginas; $i++) {
                echo "<a href='?pagina=$i'>$i</a>";
            }
            ?>
        </div>
        
        
        <div class="titulo-seccion">Servicios</div>
        <table class="catalogo-servicios">
            <thead>
                
                <tr>
                    <th>Servicio</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $consulta_servicios = "SELECT Codigo, Nombre_servicio, Tipo_servicio, Precio_venta FROM servicios";
                $result_servicios = $conex->query($consulta_servicios);
                while ($row = $result_servicios->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Nombre_servicio']) ?></td>
                        <td><?= htmlspecialchars($row['Tipo_servicio']) ?></td>
                        <td>$<?= htmlspecialchars($row['Precio_venta']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="codigo" value="<?= $row['Codigo'] ?>">
                                <input type="hidden" name="tipo" value="servicio">
                                <button type="submit" name="agregar_carrito">Agregar al Carrito</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

    </div>
</body>
</html>