<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias - Gastro BAR</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <header>
        <h1>Gracias por Contactarnos</h1>
        <nav class="navegacion-principal contenedor">
            <a href="catalogo.php">Catálogo</            <a href="carrito.php">Carrito</a>
            <a href="logout.php">Cerrar Sesión</a>
        </nav>
    </header>

    <main class="contenedor sombra">
        <p>Hemos recibido tu mensaje y te responderemos lo antes posible.</p>
        <a href="catalogo.php" class="boton">Volver al Catálogo</a>
    </main>

    <footer class="footer">
        <p>Todos los derechos reservados. Juan De la Torre Freelancer</p>
    </footer>
</body>

</html>