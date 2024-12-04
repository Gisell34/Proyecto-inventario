<?php
session_start();
session_unset(); // Limpia todas las variables de sesión
session_destroy();
header("Location: index.php");
exit();
?>