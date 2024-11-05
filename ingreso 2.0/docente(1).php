<?php
include('conexion.php');

session_start();

// Simulación de sesión de docente (debe estar conectado el docente)
$docente_id = $_SESSION['id_docente'] ?? 4; // cambiar por el id del docente real

// Función para obtener los cursos que enseña el docente
function obtenerCursos($docente_id, $conex)
{
    $sql = "SELECT Id_curso, nombre_curso FROM cursos WHERE id_docente = ?";
    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }
    $stmt->bind_param('i', $docente_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cursos = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $cursos;
}

// Función para obtener estudiantes por curso
function obtenerEstudiantesPorCurso($curso_id, $conex)
{
    $sql = "SELECT id_estudiante, nombre_estudiante, aprobado FROM estudiantes WHERE curso_id = ?";
    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }
    $stmt->bind_param('i', $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $estudiantes = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $estudiantes;
}

// Función para obtener las materias del curso
function obtenerMateriasPorCurso($curso_id, $conex)
{
    $sql = "SELECT id_materia, nombre_materia FROM materias WHERE id_curso = ?";
    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }
    $stmt->bind_param('i', $curso_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $materias = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $materias;
}

// Función para ingresar o actualizar notas
function ingresarNota($estudiante_id, $materia_id, $nota, $conex)
{
    $sql = "INSERT INTO notas (estudiante_id, materia_id, nota) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE nota = VALUES(nota)";
    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }
    $stmt->bind_param('iid', $estudiante_id, $materia_id, $nota);
    $stmt->execute();
    $stmt->close();
}

// Función para aprobar o reprobar estudiantes
function actualizarEstadoEstudiante($estudiante_id, $estado, $conex)
{
    $sql = "UPDATE estudiantes SET aprobado = ? WHERE id = ?";
    $stmt = $conex->prepare($sql);
    if (!$stmt) {
        die("Error en la preparación de la consulta: " . $conex->error);
    }
    $stmt->bind_param('ii', $estado, $estudiante_id);
    $stmt->execute();
    $stmt->close();
}

$cursos = obtenerCursos($docente_id, $conex);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['Id_curso'])) {
        $curso_id = $_POST['Id_curso'];
        $estudiantes = obtenerEstudiantesPorCurso($curso_id, $conex);
        $materias = obtenerMateriasPorCurso($curso_id, $conex);
    }

    if (isset($_POST['estudiante_id'], $_POST['materia_id'], $_POST['nota'])) {
        ingresarNota($_POST['estudiante_id'], $_POST['materia_id'], $_POST['nota'], $conex);
    }

    if (isset($_POST['estudiante_id'], $_POST['aprobado'])) {
        $estado = $_POST['aprobado'] ? 1 : 0;
        actualizarEstadoEstudiante($_POST['estudiante_id'], $estado, $conex);
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Módulo de Docentes</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1>Módulo de Docentes</h1>

    <h2>Seleccionar Curso</h2>
    <form method="post">
        <label for="Id_curso">Curso:</label>
        <select name="Id_curso" id="Id_curso">
            <?php foreach ($cursos as $curso): ?>
                <option value="<?php echo $curso['Id_curso']; ?>"><?php echo $curso['nombre_curso']; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Ver Estudiantes</button>
    </form>

    <?php if (isset($estudiantes) && isset($materias)): ?>
        <h2>Estudiantes del Curso</h2>
        <form method="post">
            <input type="hidden" name="Id_curso" value="<?php echo $curso_id; ?>">
            <table>
                <tr>
                    <th>Nombre</th>
                    <th>Materia</th>
                    <th>Nota</th>
                    <th>Aprobado</th>
                    <th>Actualizar Nota</th>
                    <th>Aprobar/Reprobar</th>
                </tr>
                <?php foreach ($estudiantes as $estudiante): ?>
                    <tr>
                        <td><?php echo $estudiante['nombre_estudiante']; ?></td>
                        <td>
                            <select name="materia_id">
                                <?php foreach ($materias as $materia): ?>
                                    <option value="<?php echo $materia['id_materia']; ?>"><?php echo $materia['nombre_materia']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="nota" required>
                            <input type="hidden" name="estudiante_id" value="<?php echo $estudiante['id_estudiante']; ?>">
                        </td>
                        <td><?php echo $estudiante['aprobado'] ? 'Sí' : 'No'; ?></td>
                        <td><button type="submit" name="ingresar_nota">Ingresar Nota</button></td>
                        <td>
                            <select name="aprobado">
                                <option value="1" <?php if ($estudiante['aprobado']) echo 'selected'; ?>>Aprobar</option>
                                <option value="0" <?php if (!$estudiante['aprobado']) echo 'selected'; ?>>Reprobar</option>
                            </select>
                            <button type="submit" name="cambiar_estado">Cambiar Estado</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </form>
    <?php endif; ?>
</body>

</html>