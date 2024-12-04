<?php
session_start();
if (!isset($_SESSION['user_id'])) { // Cambia 'user_id' según tu implementación
    header("Location: login.php");
    exit();
}
// Evitar que el navegador almacene en caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
?>