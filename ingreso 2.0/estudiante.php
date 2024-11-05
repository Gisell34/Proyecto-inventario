<?php
// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('conexion.php');

// Inicializar variables
$estudiante_id = '';
$nombre_estudiante = $materias = $notas = $docentes = $informacion_personal = '';

// Verificar si se ha enviado el formulario para buscar la información del estudiante
if (isset($_GET['estudiante_id'])) {
    $estudiante_id = $_GET['estudiante_id'];

    // Consultar la información personal del estudiante junto con el curso
    $sql = "SELECT nombre_estudiante, curso_id FROM estudiantes WHERE id_estudiante = ?";
    $stmt = $conex->prepare($sql);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $stmt->bind_result($nombre_estudiante, $curso_id);

    if ($stmt->fetch()) {
        $informacion_personal = "
            <p><strong>Nombre:</strong> $nombre_estudiante</p>
            <p><strong>Curso ID:</strong> $curso_id</p>";
    } else {
        echo "<script>alert('Estudiante no encontrado.');</script>";
    }

    $stmt->close();

    // Consultar las materias que cursa el estudiante y sus notas
    $sql = "SELECT materias.nombre_materia, notas.nota 
            FROM materias 
            JOIN notas ON materias.id_materia = notas.materia_id 
            WHERE notas.estudiante_id = ?";
    $stmt = $conex->prepare($sql);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $notas .= "<p>" . $row['nombre_materia'] . ": " . $row['nota'] . " (" . ($row['nota'] >= 3.0 ? "Aprobado" : "Reprobado") . ")</p>";
    }
    $stmt->close();

    // Consultar la lista de materias que cursa el estudiante
    $sql = "SELECT nombre_materia 
            FROM materias 
            JOIN notas ON materias.id_materia = notas.materia_id 
            WHERE notas.estudiante_id = ?";
    $stmt = $conex->prepare($sql);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $materias .= "<p>" . $row['nombre_materia'] . "</p>";
    }
    $stmt->close();

    // Consultar los docentes que enseñan al estudiante
    $sql = "SELECT DISTINCT docentes.nombre_docente 
            FROM docentes 
            JOIN materias ON docentes.id_docente = materias.docente_id 
            JOIN notas ON materias.id_materia = notas.materia_id 
            WHERE notas.estudiante_id = ?";
    $stmt = $conex->prepare($sql);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $stmt->bind_param("i", $estudiante_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $docentes .= "<p>" . $row['nombre_docente'] . "</p>";
    }
    $stmt->close();
}

// Verificar si se ha enviado el formulario para modificar la información
if (isset($_POST['modificar'])) {
    $estudiante_id = $_POST['estudiante_id'];
    $nombre_estudiante = $_POST['nombre_estudiante'];
    $curso_id = $_POST['curso_id'];

    // Actualizar la información del estudiante incluyendo curso_id
    $sql = "UPDATE estudiantes SET nombre_estudiante = ?, curso_id = ? WHERE id_estudiante = ?";
    $stmt = $conex->prepare($sql);

    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . $conex->error);
    }

    $stmt->bind_param("sii", $nombre_estudiante, $curso_id, $estudiante_id);

    if ($stmt->execute()) {
        echo "<script>alert('Información del estudiante modificada con éxito.');</script>";
    } else {
        echo "<script>alert('Error al modificar la información del estudiante.');</script>";
    }

    $stmt->close();
}
