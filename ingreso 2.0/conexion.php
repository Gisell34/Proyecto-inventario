<?php
$conex = new mysqli("localhost", "root", "", "login");

if ($conex->connect_errno) {
	echo "No hay conexión: (" . $conex->connect_errno . ") " . $conex->connect_error;
}
