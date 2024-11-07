<?php
session_start();
include('conexion.php');

// Verificar si la conexión a la base de datos es exitosa
if ($conex->connect_error) {
    die("Error de conexión: " . $conex->connect_error);
}

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();
$servicios = isset($_SESSION['servicios']) ? $_SESSION['servicios'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['agregar_carrito'])) {
    $codigo = $_POST['codigo'];
    $tipo = $_POST['tipo'];
    $cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1; // Valor predeterminado 1 si no se define cantidad

    // Identificar si es un producto o un servicio y almacenar en el carrito
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

            // Validar que el precio es un número antes de proceder
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

                echo "<script>alert('Artículo añadido al carrito.');</script>";
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
        }

        .catalogo-container {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .catalogo-table {
            width: 100%;
            border-collapse: collapse;
        }

        .catalogo-table th,
        .catalogo-table td {
            padding: 10px;
            text-align: center;
            border: 3px double #9bbec0;
        }

        .catalogo-table th {
            background-color: #9bbec0;
            color: white;
        }

        .catalogo-table td form {
            display: inline;
        }

        .catalogo-table td button {
            padding: 5px 10px;
            background-color: #9bbec0;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .catalogo-table td button:hover {
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
    <div class="catalogo-container">
        <h1>Catálogo de Productos y Servicios</h1>

        <!-- Mostrar Productos -->
        <h2>Productos</h2>
        <table class="catalogo-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre del Producto</th>
                    <th>Tipo</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $consulta_productos = "SELECT p.Codigoproducto, p.Nombre_producto, p.Tipo_producto, i.precio_venta 
                                       FROM producto p 
                                       JOIN inventario i ON p.Codigoproducto = i.Codigoproducto";
                $result_productos = $conex->query($consulta_productos);
                while ($row = $result_productos->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Codigoproducto']) ?></td>
                        <td><?= htmlspecialchars($row['Nombre_producto']) ?></td>
                        <td><?= htmlspecialchars($row['Tipo_producto']) ?></td>
                        <td>$<?= htmlspecialchars($row['precio_venta']) ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="codigo" value="<?= $row['Codigoproducto'] ?>">
                                <input type="hidden" name="tipo" value="producto">
                                <select name="cantidad" required>
                                    <?php for ($i = 1; $i <= 100; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                        </td>
                        <td>
                                <button type="submit" name="agregar_carrito">Agregar al Carrito</button>
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

        <!-- Mostrar Servicios -->
        <h2>Servicios</h2>
        <table class="catalogo-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre del Servicio</th>
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
                        <td><?= htmlspecialchars($row['Codigo']) ?></td>
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

        <a class="regresar" href="user.php">Regresar</a>
    </div>
</body>
</html>