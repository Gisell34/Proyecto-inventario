<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MUKAP</title>
    <link rel="preload" href="css/normalize.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preload" href="css/normalize.css" as="style">
<link rel="stylesheet" href="css/normalize.css">
<link rel="preload" href="css/styles.css" as="style">
<link href="css/styles.css" rel="stylesheet">
    <style>
        #ubicacion {
            background-color: #f0e5d8;
            padding: 40px;
            text-align: center;
            color: #333;
            border-radius: 10px;
            margin: 30px auto;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #ubicacion h2 {
            font-size: 2em;
            color: #8171b8;
        }

        #ubicacion p {
            font-size: 1.2em;
            margin-top: 10px;
            color: #555;
        }

        #ubicacion a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #8171b8;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        #ubicacion a:hover {
            background-color: #8171b8;
        }
    </style>
</head>

<body>
    <header id="sobre-nosotros">
        <h1 class="titulo">Gastro Bar Licores Don Chepe</h1>
        <h2><span>Tienda Online</span></h2>
        <!-- Añadir más contenido "Sobre Nosotros" -->
    </header>
    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <?php if (!isset($_SESSION['usuario'])): ?>
                <a href="login.php">Login</a>
            <?php endif; ?>

            <a href="#sobre-nosotros">Sobre nosotros</a>
            <a href="#servicios">Servicios</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="#contacto">Contacto</a>
            <a href="#ubicacion">Encuéntranos</a>

            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="<?php echo ($_SESSION['rol'] === 'Administrador') ? 'admin.php' : 'user.php'; ?>">
                    <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                </a>
                <a href="logout.php">Cerrar sesión</a>
            <?php endif; ?>
        </nav>
    </div>

    <section class="descarga">
        <div class="contenido-descarga">
        </div>
    </section>

    <main id="servicios" class="contenedor sombra">
        <h2>Mis Servicios</h2>

        <div class="servicios">
            <section class="servicio">
                <h3>Servicio De Transporte</h3>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bus" width="84"
                        height="84" viewBox="0 0 24 24" stroke-width="1" stroke="#00abfb" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M18 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M4 17h-2v-11a1 1 0 0 1 1 -1h14a5 7 0 0 1 5 7v5h-2m-4 0h-8" />
                        <path d="M16 5l1.5 7l4.5 0" />
                        <path d="M2 10l15 0" />
                        <path d="M7 5l0 5" />
                        <path d="M12 5l0 5" />
                    </svg>
                </div>
                <p>¡Viaja con comodidad y seguridad con nuestro Servicio de Transporte! Ofrecemos traslados rápidos y
                    confiables, adaptados a tus necesidades. Disfruta de un servicio de calidad con conductores
                    profesionales y una flota moderna. ¡Elige la excelencia en cada viaje!</p>
            </section>

            <section class="servicio">
                <h3>Compras Online Y Domicilios</h3>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-shopping-cart"
                        width="84" height="84" viewBox="0 0 24 24" stroke-width="1" stroke="#00abfb" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M17 17h-11v-14h-2" />
                        <path d="M6 5l14 1l-1 7h-13" />
                    </svg>
                </div>
                <p>¡Disfruta de la comodidad de comprar desde casa con nuestro servicio de Compras Online y Domicilios!
                    Ofrecemos una amplia variedad de productos con entrega rápida y segura hasta tu puerta. ¡Ahorra
                    tiempo y esfuerzo, compra en línea y recibe todo lo que necesitas sin salir de casa!</p>
            </section>

            <section class="servicio">
                <h3>Comidas Y Algo Más...</h3>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-meat" width="88"
                        height="88" viewBox="0 0 24 24" stroke-width="1" stroke="#00abfb" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M13.62 8.382l1.966 -1.967a2 2 0 1 1 3.414 -1.415a2 2 0 1 1 -1.413 3.414l-1.82 1.821" />
                        <path
                            d="M5.904 18.596c2.733 2.734 5.9 4 7.07 2.829c1.172 -1.172 -.094 -4.338 -2.828 -7.071c-2.733 -2.734 -5.9 -4 -7.07 -2.829c-1.172 1.172 .094 4.338 2.828 7.071z" />
                        <path d="M7.5 16l1 1" />
                        <path
                            d="M12.975 21.425c3.905 -3.906 4.855 -9.288 2.121 -12.021c-2.733 -2.734 -8.115 -1.784 -12.02 2.121" />
                    </svg>
                </div>
                <p>¡Descubre la delicia de lo inesperado con Comidas Y Algo Más! Ofrecemos una variada selección de
                    platos exquisitos, acompañados de sorpresas que harán de cada pedido una experiencia única. Disfruta
                    de sabores incomparables y ese toque especial que solo nosotros te brindamos. ¡Ordena hoy y
                    convierte tu comida en un momento memorable!</p>
            </section>
        </div>
    </main>

    <?php if(isset($_SESSION['usuario'])): ?>
        <section id="catalogo">
            <?php include('catalogo.php'); ?>
        </section>
    <?php endif; ?>

    <section id="contacto" class="contenedor sombra">
        <h2>Contacto</h2>

        <form class="formulario" action="procesar_contacto.php" method="POST">
            <fieldset>
                <legend>Contáctanos llenando todos los campos</legend>

                <div class="contenedor-campos">
                    <div class="campo">
                        <label for="nombre">Nombre</label>
                        <input class="input-text" type="text" id="nombre" name="nombre" placeholder="Tu Nombre" required>
                    </div>

                    <div class="campo">
                        <label for="telefono">Teléfono</label>
                        <input class="input-text" type="tel" id="telefono" name="telefono" placeholder="Tu Teléfono">
                    </div>

                    <div class="campo">
                        <label for="correo">Correo</label>
                        <input class="input-text" type="email" id="correo" name="correo" placeholder="Tu Email" required>
                    </div>

                    <div class="campo">
                        <label for="mensaje">Mensaje</label>
                        <textarea class="input-text" id="mensaje" name="mensaje" placeholder="Tu Mensaje" required></textarea>
                    </div>
                </div>
                <div class="alinear-derecha flex">
                    <input class="boton w-sm-100" type="submit" value="Enviar">
                </div>
            </fieldset>
        </form>
    </section>

    <<section id="ubicacion">
        <h2>¡Ven y visítanos!</h2>
        <p>Estamos ubicados en el corazón de la ciudad, a pocos minutos del centro. ¡Te esperamos con el mejor ambiente!</p>
        <a href="https://www.google.com/maps/place/Mar%C3%ADa+Mar%C3%ADa+cantina/@4.7390934,-74.0846248,15z/data=!4m17!1m10!3m9!1s0x8e3f85b3056fffc1:0xa77ca8dfb28fb29f!2zTWFyw61hIE1hcsOtYSBjYW50aW5h!8m2!3d4.7390723!4d-74.084526!10e5!14m1!1BCgIgAQ!16s%2Fg%2F11lkz8k4_w!3m5!1s0x8e3f85b3056fffc1:0xa77ca8dfb28fb29f!8m2!3d4.7390723!4d-74.084526!16s%2Fg%2F11lkz8k4_w?entry=ttu&g_ep=EgoyMDI0MTAyOS4wIKXMDSoASAFQAw%3D%3D">Cra. 91 #145a-22, Bogotá</a>
    </section>
</body>

</html>

    <footer class="footer">
        <p>Todos los derechos reservados. Juan De la Torre Freelancer</p>
    </footer>

    <script>
        document.querySelector('.formulario').addEventListener('submit', function(e) {
            const nombre = document.getElementById('nombre').value.trim();
            const correo = document.getElementById('correo').value.trim();
            const mensaje = document.getElementById('mensaje').value.trim();

            if (nombre === '' || correo === '' || mensaje === '') {
                e.preventDefault();
                alert('Por favor, completa todos los campos obligatorios.');
            }
        });
    </script>
</body>

</html>