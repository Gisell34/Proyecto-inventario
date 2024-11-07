<?php

include('conexion.php');

// Consulta para obtener los productos del inventario
$sql = "SELECT Codigoproducto, Tipo_producto, Nombre_producto, Cantidad, Precio FROM inventario";
$resultado = $conex->query($sql);
?>

<!DOCTYPE html>

<html>

<head>
    <title>Catálogo de Productos</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <h1>Catálogo de Productos</h1>
    <div class="catalogo">
        <?php

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                echo "<div class='producto'>
                        <h2>" . $row["Nombre_producto"] . "</h2>
                        <p><strong>Código:</strong> " . $row["Codigoproducto"] . "</p>
                        <p><strong>Tipo:</strong> " . $row["Tipo_producto"] . "</p>
                        <p><strong>Cantidad:</strong> " . $row["Cantidad"] . "</p>
                        <p><strong>Precio:</strong> $" . $row["Precio"] . "</p>
                      </div>";
            }
        } else {
            echo "<p>No se encontraron productos.</p>";
        }
        ?>

    </div>

</body>

</html>