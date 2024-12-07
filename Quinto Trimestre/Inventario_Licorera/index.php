<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maria maria cantina</title>
    <link rel="preload" href="css/normalize.css" as="style">
    <link rel="stylesheet" href="css/normalize.css">
    <link href="https://fonts.googleapis.com/css2?family=Krub:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preload" href="css/styles.css" as="style">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Aseguramos que el body y html ocupen toda la altura de la pantalla */
        html, body {
            height: 100%; /* Esto asegura que el cuerpo y la raíz ocupen el 100% de la pantalla */
            margin: 0;
            padding: 0;
            font-family: 'Krub', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
        }

        .galeria {
            margin: 20px;
        }
/* Causante de error scroll
        #image-container {
            display: flex;
            overflow: visible;  Asegura que el contenido no sea recortado
            width: 60%;
            margin: 0 auto;
        }
  */

        .image {
            display: none;
            width: 20%;
        }

        .image.active {
            display: block;
        }

        .nav-buttons {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .nav-buttons button {
            margin: 0 10px;
            padding: 5px 10px;
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .nav-buttons button:hover {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
        }

        .prev,
        .next {
            position: fixed; /* Cambiado a fixed para estar siempre visibles */
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(0, 0, 0, 0.5);
            color: black;
            padding: 10px;
            border: none;
            cursor: pointer;
            font-size: 18px;
            border-radius: 5px;
            z-index: 1000;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .nav-bg {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            padding: 10px 0;
        }

        .navegacion-principal a {
            color: #fcfbfa;
            margin: 0 10px;
            text-decoration: none;
            font-weight: bold;
        }

        .navegacion-principal a:hover {
            color: #fcfbfa;
        }

        header {
            background-image: url('https://scontent.fbog3-3.fna.fbcdn.net/v/t39.30808-6/464285521_1072657814863172_6609652457754339681_n.jpg?_nc_cat=105&ccb=1-7&_nc_sid=a5f93a&_nc_ohc=dwsoIIgRTt0Q7kNvgFIiBep&_nc_zt=23&_nc_ht=scontent.fbog3-3.fna&_nc_gid=ADcTPqur4uhhhfRzwiVCMVV&oh=00_AYATJUo4SbtbcfTi3KRrTuH7HKFl_6Ab400rnDtRjED4kg&oe=672D98D7');
            text-align: center;
            padding: 40px 20px;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        header h1 {
            font-size: 4em;
            color: #2c4749;
        }

        header h2 span {
            color: #fcfbfa;
        }

        header p {
            font-size: 3.2em;
            margin-top: 10px;
            color: #fff;
        }

        #servicios {
            background: linear-gradient(to right, #9495b9, #7bc0c5);
            padding: 40px 20px;
            margin-top: 30px;
            color: #868df0;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #servicios h2 {
            font-size: 2em;
            color: #fcfbfa;
        }

        .servicios {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 35px;
        }

        .servicio {
            background-color: #f0e5d8;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease;
        }

        .servicio:hover {
            background-color: #b18888;
            color: #e4e13b;
        }

        .servicio img {
            width: 30px;
            height: 30px;
        }

        .servicio p {
            font-weight: bold;
            margin-top: 10px;
        }

        #contacto {
            background-color: #e9db8c;
            padding: 60px 80px;
            color: #333;
            border-radius: 10px;
            margin: 30px 0; /* Ajustado el margen para mejor espaciado */
            max-width: 900px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-left: auto;
            margin-right: auto; /* Centrado el formulario */
        }

        #contacto h3 {
            font-size: 2em;
            color: #8171b8;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            color: #333;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="Telefono"],
        form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        form input[type="submit"] {
            background-color: #8171b8;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #f0e5d8;
        }

        .redes-sociales a {
            font-size: 24px;
            color: #E4405F, #25D366;
            margin: 0 10px;
            text-decoration: none;
        }

        .icono-whatsapp {
            color: #25D366;
        }

        .icono-instagram {
            color: #E4405F;
        }

        .icono-whatsapp:hover,
        .icono-instagram:hover {
            color: #34b7f1;
        }

        footer {
            background-color: #2c4749;
            color: #fff;
            text-align: center;
            padding: 20px 0;
            margin-top: 30px;
        }

        footer p {
            margin: 0;
        }

        #ubicacion {
            background-color: #f0e5d8;
            padding: 40px;
            text-align: center;
            color: #333;
            border-radius: 10px;
            margin: 30px auto;
            max-width: 500px;
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
    <div class="nav-bg">
        <nav class="navegacion-principal contenedor">
            <?php if (!isset($_SESSION['usuario'])): ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
            <a href="#sobre-nosotros">Sobre nosotros</a>
            <a href="#servicios">Servicios</a>
            <a href="#contacto">Contacto</a>
            <a href="catalogo.php">Catálogo</a>
            <a href="#ubicacion">Encuéntranos</a>
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="<?php echo ($_SESSION['rol'] === 'Administrador') ? 'admin.php' : 'user.php'; ?>">
                    <?php echo htmlspecialchars($_SESSION['usuario']); ?>
                </a>
                <a href="logout.php">Cerrar sesión</a>
            <?php endif; ?>
        </nav>
    </div>

    <header id="sobre-nosotros">
        <h1 class="titulo">María María Cantina</h1>
        <h2><span></span></h2>
        <p>Vive la experiencia mexicana en vivo💥
            Banda, Mariachi, Popular 🎙🎺🎤🎺
            Eventos y fechas especiales</p>
    </header>

    <section id="servicios">
        <h2>Nuestros Servicios</h2>
        <div class="servicios">
            <section class="servicio">
                <h3>Servicio De Transporte</h3>
                <div class="iconos">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-bus" width="84"
                        height="" viewBox="0 0 24 24" stroke-width="1" stroke="#00abfb" fill="none"
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
                        width="90" height="84" viewBox="0 0 24 24" stroke-width="1" stroke="#00abfb" fill="none"
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
                        height="90" viewBox="0 0 24 24" stroke-width="1" stroke="#00abfb" fill="none"
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

        <section id="contacto">
            <h3>Contacto</h3>
            <form action="procesar_contacto.php" method="post">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" maxlength="100" required>
                
                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" maxlength="100" required>
                
                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
                
                <button type="submit">Enviar</button>
            </form>

            <div class="redes-sociales">
                <h4>¡ Reserva en nuestras redes ! 👇:</h4>
                <a href="https://api.whatsapp.com/send?phone=573227271249" class="icono-whatsapp" target="_blank">
                    <i class="fab fa-whatsapp"></i> WhatsApp
                </a>
                <h5>Siguenos en:</h5>
                <a href="https://www.instagram.com/mariamariacantina/?hl=es" class="icono-instagram" target="_blank">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
            </div>
        </section>

        <section id="ubicacion">
            <h2>¡Ven y visítanos!</h2>
            <p>Estamos ubicados en el corazón de la ciudad, a pocos minutos del centro. ¡Te esperamos con el mejor ambiente!</p>
            <a href="https://www.google.com/maps/place/Mar%C3%ADa+Mar%C3%ADa+cantina/@4.7390934,-74.0846248,15z/data=!4m17!1m10!3m9!1s0x8e3f85b3056fffc1:0xa77ca8dfb28fb29f!2zTWFyw61hIE1hcsOtYSBjYW50aW5h!8m2!3d4.7390723!4d-74.084526!10e5!14m1!1BCgIgAQ!16s%2Fg%2F11lkz8k4_w!3m5!1s0x8e3f85b3056fffc1:0xa77ca8dfb28fb29f!8m2!3d4.7390723!4d-74.084526!16s%2Fg%2F11lkz8k4_w?entry=ttu&g_ep=EgoyMDI0MTAyOS4wIKXMDSoASAFQAw%3D%3D">Cra. 91 #145a-22, Bogotá</a>
        </section>
</body>

</html>