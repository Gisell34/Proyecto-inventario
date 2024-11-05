<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Formulario de Registro de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #dddfc7;
            color: #444552;
            margin: 0;
            padding: 0;
        }

        header {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
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
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            transition: background-color 0.3s ease;
        }

        nav a:hover {
            background-color: #899da4;
        }

        .admin-panel {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .admin-panel h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .admin-panel a {
            display: block;
            margin: 10px 0;
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .admin-panel a:hover {
            background-color: #899da4;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form label {
            font-weight: bold;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="number"],
        form textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form input[type="submit"] {
            padding: 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #899da4;
        }
    </style>
</head>

<body>
    <header>
        <nav>
            <a href="admin.php" class="back-to-admin">Volver al panel del Administrador</a>
        </nav>
    </header>

    <div class="admin-panel">
        <h2>Registro de Productos e Inventario</h2>
        <form id="producto_form" action="guardar_producto.php" method="POST" class="form-styles">

            <label for="Nombre_producto">Nombre:</label>
            <input type="text" id="Nombre_producto" name="Nombre_producto" required><br><br>

            <button type="button" id="buscar_producto">Buscar Producto</button>
            <div id="form_data" style="display: none;">

                <label for="Fabricante">Fabricante:</label>
                <input type="text" id="Fabricante" name="Fabricante"><br><br>

                <label for="Tipo_producto">Tipo de Producto:</label>
                <input type="text" id="Tipo_producto" name="Tipo_producto"><br><br>

                <label for="Especificaciones">Especificaciones:</label><br>
                <textarea id="Especificaciones" name="Especificaciones" rows="4"></textarea><br><br>

                <label for="cantidad_anterior">Cantidad Anterior:</label>
                <input type="number" id="cantidad_anterior" name="cantidad_anterior" readonly><br><br>

                <label for="cantidad">Cantidad a agregar:</label>
                <input type="number" id="cantidad" name="cantidad"><br><br>

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" step="0.01"><br><br>

                <label for="existencias_total">Existencias Totales:</label>
                <input type="number" id="existencias_total" name="existencias_total" readonly><br><br>

                <label for="stock_minimo">Stock Mínimo:</label>
                <input type="number" id="stock_minimo" name="stock_minimo" readonly><br><br>

                <label for="stock_maximo">Stock Máximo:</label>
                <input type="number" id="stock_maximo" name="stock_maximo" readonly><br><br>

                <label for="salida">Salida:</label>
                <input type="number" id="salida" name="salida" readonly><br><br>

                <input type="submit" value="Guardar Producto">
                <input type="submit" name="accion" value="Guardar y Agregar Otro">
            </div>
        </form>
    </div>

    <script>
        document.getElementById('buscar_producto').addEventListener('click', function(event) {
            const nombreProducto = document.getElementById('Nombre_producto').value;

            if (!nombreProducto) {
                alert('Por favor, ingrese el nombre del producto para buscar.');
                return;
            }

            fetch('buscar_producto.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `Nombre_producto=${nombreProducto}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    document.getElementById('form_data').style.display = 'block';
                    document.getElementById('Fabricante').value = data.Fabricante;
                    document.getElementById('Tipo_producto').value = data.Tipo_producto;
                    document.getElementById('Especificaciones').value = data.Especificaciones;
                    document.getElementById('cantidad_anterior').value = data.Cantidad_producto;
                    document.getElementById('existencias_total').value = data.Existencias_totales;
                    document.getElementById('stock_minimo').value = data.Stock_minimo;
                    document.getElementById('stock_maximo').value = data.Stock_maximo;
                    document.getElementById('salida').value = data.Salida;
                } else {
                    alert('Producto no encontrado. Puede agregar un nuevo producto.');
                    document.getElementById('form_data').style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error al buscar el producto:', error);
            });
        });
    </script>
</body>
</html>