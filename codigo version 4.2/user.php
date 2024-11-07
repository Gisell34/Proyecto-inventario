<?php
session_start();

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "Usuario") {
    header("Location: login.php");
    exit();
}

include('conexion.php');

$carrito = isset($_SESSION['carrito']) ? $_SESSION['carrito'] : array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['eliminar_producto'])) {
        $codigo_producto = $_POST['codigo_producto'];
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            unset($_SESSION['carrito'][$codigo_producto]);
            echo "<script>alert('Producto eliminado del carrito correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo eliminar el producto del carrito.');</script>";
        }
    }

    if (isset($_POST['modificar_producto'])) {
        $codigo_producto = $_POST['codigo_producto'];
        $nueva_cantidad = $_POST['cantidad'];
        if (isset($_SESSION['carrito'][$codigo_producto])) {
            $_SESSION['carrito'][$codigo_producto] = $nueva_cantidad;
            echo "<script>alert('Cantidad modificada correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } else {
            echo "<script>alert('No se pudo modificar la cantidad del producto.');</script>";
        }
    }

    if (isset($_POST['comprar'])) {
        $id_usuario = $_SESSION['usuario_id']; // Ajustar según la sesión
        $fecha_compra = date("Y-m-d");
        $total = 0;

        // Iniciar la transacción
        $conex->begin_transaction();

        try {
            // Insertar la compra
            $sql = "INSERT INTO compras (id_usuario, fecha_compra, total) VALUES (?, ?, ?)";
            if ($stmt = $conex->prepare($sql)) {
                $stmt->bind_param("isd", $id_usuario, $fecha_compra, $total);
                $stmt->execute();
                $id_compra = $stmt->insert_id;
                $stmt->close();
            } else {
                throw new Exception("Error en la preparación de la consulta SQL para guardar la compra: " . $conex->error);
            }

            // Insertar los detalles de la compra
            foreach ($carrito as $codigo => $cantidad) {
                $sql = "SELECT precio_venta FROM inventario WHERE Codigoproducto = ?";
                if ($stmt_producto = $conex->prepare($sql)) {
                    $stmt_producto->bind_param("i", $codigo);
                    $stmt_producto->execute();
                    $resultado_producto = $stmt_producto->get_result();
                    if ($resultado_producto->num_rows > 0) {
                        $row_producto = $resultado_producto->fetch_assoc();
                        $precio_venta = $row_producto['precio_venta'];
                        $valor_total = $precio_venta * $cantidad;
                        $total += $valor_total;

                        // Insertar en detalles_compras
                        $sql_detalle = "INSERT INTO detalles_compras (id_compra, codigoproducto, cantidad, precio) VALUES (?, ?, ?, ?)";
                        if ($stmt_detalle = $conex->prepare($sql_detalle)) {
                            $stmt_detalle->bind_param("iidi", $id_compra, $codigo, $cantidad, $precio_venta);
                            $stmt_detalle->execute();
                            $stmt_detalle->close();
                        } else {
                            throw new Exception("Error en la preparación de la consulta SQL para insertar detalles de compra: " . $conex->error);
                        }

                        // Actualizar inventario
                        $sql_inventario = "UPDATE inventario SET salidas = salidas + ?, entradas = entradas - ? WHERE Codigoproducto = ?";
                        if ($stmt_inventario = $conex->prepare($sql_inventario)) {
                            $stmt_inventario->bind_param("iis", $cantidad, $cantidad, $codigo);
                            $stmt_inventario->execute();
                            $stmt_inventario->close();
                        } else {
                            throw new Exception("Error en la preparación de la consulta SQL para actualizar el inventario: " . $conex->error);
                        }
                    }
                    $stmt_producto->close();
                } else {
                    throw new Exception("Error en la preparación de la consulta SQL para obtener precio de producto: " . $conex->error);
                }
            }

            // Actualizar el total en la tabla de compras
            $sql_actualizar_total = "UPDATE compras SET total = ? WHERE id_compra = ?";
            if ($stmt_total = $conex->prepare($sql_actualizar_total)) {
                $stmt_total->bind_param("di", $total, $id_compra);
                $stmt_total->execute();
                $stmt_total->close();
            } else {
                throw new Exception("Error en la preparación de la consulta SQL para actualizar el total de la compra: " . $conex->error);
            }

            // Limpiar el carrito después de la compra
            $_SESSION['carrito'] = array();

            // Confirmar la transacción
            $conex->commit();

            echo "<script>alert('Compra realizada correctamente.');</script>";
            echo "<script>window.location.replace('user.php');</script>";
            exit();
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conex->rollback();
            echo "<script>alert('Error en la compra: " . $e->getMessage() . "');</script>";
        }
    }
}

$consulta = "SELECT c.Nombres, c.Apellidos, c.Cedula, c.Direccion, c.Correo_electronico, c.Telefono, c.Ciudad FROM cliente c JOIN usuarios u ON c.Codigocliente = u.Id_usuario WHERE u.Usuario = ?";
if ($stmt = $conex->prepare($consulta)) {
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $row = $resultado->fetch_assoc();
    $stmt->close();
} else {
    echo "Error en la preparación de la consulta SQL para recuperar datos del usuario: " . $conex->error;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Panel de usuario</title>
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

        nav {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background: linear-gradient(to right, #7bc0c5, #9495b9);
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background: linear-gradient(to right, #7bc0c5, #9495b9);
        }

        .user-panel {
            max-width: 1000px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
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
            background-color: #9bbec0;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .user-panel a:hover {
            background-color: #899da4;
        }

        .perfil-usuario {
            display: none; /* Oculta la sección de perfil de usuario */
        }

        .user-section {
            margin-top: 20px;
        }

        .list-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="catalogo.php">Catálogo de Productos y Servicios</a>
            <a href="carrito.php">Carrito de Compras</a>
            <a href="actualizar_perfil.php">Actualizar Perfil</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <div class="user-panel">
        <h1>Bienvenido usuario</h1>

        <div class="perfil-usuario">
            <h2>Perfil de usuario</h2>
            <p>Usuario: <?= htmlspecialchars($_SESSION['usuario']); ?></p>
            <p>Nombres: <?= isset($row['Nombres']) ? htmlspecialchars($row['Nombres']) : 'N/A'; ?></p>
            <p>Apellidos: <?= isset($row['Apellidos']) ? htmlspecialchars($row['Apellidos']) : 'N/A'; ?></p>
            <p>Cédula: <?= isset($row['Cedula']) ? htmlspecialchars($row['Cedula']) : 'N/A'; ?></p>
            <p>Dirección: <?= isset($row['Direccion']) ? htmlspecialchars($row['Direccion']) : 'N/A'; ?></p>
            <p>Correo electrónico: <?= isset($row['Correo_electronico']) ? htmlspecialchars($row['Correo_electronico']) : 'N/A'; ?></p>
            <p>Teléfono: <?= isset($row['Telefono']) ? htmlspecialchars($row['Telefono']) : 'N/A'; ?></p>
            <p>Ciudad: <?= isset($row['Ciudad']) ? htmlspecialchars($row['Ciudad']) : 'N/A'; ?></p>
        </div>

        <div class="user-section">
            <h2>Estado de tus solicitudes</h2>
            <div class="list-container">
                <h3>Productos en el carrito</h3>
                <?php
                if (!empty($carrito)) {
                    echo "<table class='user-table'>
                            <thead>
                                <tr>
                                    <th>Código de Producto</th>
                                    <th>Nombre del Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Valor Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>";
                    foreach ($carrito as $codigo => $cantidad) {
                        $sql = "SELECT Nombre_producto, precio_venta FROM inventario WHERE Codigoproducto = ?";
                        if ($stmt_producto = $conex->prepare($sql)) {
                            $stmt_producto->bind_param("i", $codigo);
                            $stmt_producto->execute();
                            $resultado_producto = $stmt_producto->get_result();
                            if ($resultado_producto->num_rows > 0) {
                                $row_producto = $resultado_producto->fetch_assoc();
                                $precio_venta = $row_producto['precio_venta'];
                                $valor_total = $precio_venta * $cantidad;
                                echo "<tr>
                                        <td>$codigo</td>
                                        <td>{$row_producto['Nombre_producto']}</td>
                                        <td>
                                            <form method='POST' action='user.php'>
                                                <input type='hidden' name='codigo_producto' value='$codigo'>
                                                <select name='cantidad' onchange='this.form.submit()'>";
                                                for ($i = 1; $i <= 100; $i++) {
                                                    $selected = ($i == $cantidad) ? "selected" : "";
                                                    echo "<option value='$i' $selected>$i</option>";
                                                }
                                                echo "</select>
                                            </form>
                                        </td>
                                        <td>\$$precio_venta</td>
                                        <td>\$$valor_total</td>
                                        <td>
                                            <form method='POST' action='user.php' style='display:inline-block'>
                                                <input type='hidden' name='codigo_producto' value='$codigo'>
                                                <button type='submit' name='eliminar_producto'>Eliminar</button>
                                            </form>
                                            <form method='POST' action='user.php' style='display:inline-block'>
                                                <input type='hidden' name='codigo_producto' value='$codigo'>
                                                <button type='submit' name='modificar_producto'>Modificar</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                            $stmt_producto->close();
                        } else {
                            echo "Error en la preparación de la consulta SQL para obtener nombre de producto: " . $conex->error;
                        }
                    }
                    echo "</tbody></table>";
                } else {
                    echo "<p>No tienes productos en el carrito.</p>";
                }
                ?>
            </div>
            <form method="POST" action="user.php">
                <button type="submit" name="comprar">Comprar</button>
            </form>
        </div>
    </div>
</body>
</html>