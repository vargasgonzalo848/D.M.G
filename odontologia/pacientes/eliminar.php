<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

$id = intval($_GET['id']);

$sqlCheckTratamientos = "SELECT COUNT(*) as total FROM Tratamientos WHERE id_pacientes = ?";
$stmtCheck = $conn->prepare($sqlCheckTratamientos);
$stmtCheck->bind_param("i", $id);
$stmtCheck->execute();
$resultTratamientos = $stmtCheck->get_result();
$tratamientos = $resultTratamientos->fetch_assoc()['total'];
$stmtCheck->close();

if ($tratamientos > 0) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Error al eliminar</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>⚠️ No se puede eliminar el paciente</h4>
                <p>Este paciente tiene <strong>$tratamientos tratamiento(s)</strong> activo(s). Debe eliminar o finalizar los tratamientos antes de eliminar al paciente.</p>
                <a href='lista.php' class='btn btn-primary'>Volver al listado</a>
            </div>
        </div>
    </body>
    </html>";
    exit();
}

$stmtTurnos = $conn->prepare("DELETE FROM Turnos WHERE id_pacientes = ?");
$stmtTurnos->bind_param("i", $id);
$stmtTurnos->execute();
$turnosEliminados = $stmtTurnos->affected_rows;
$stmtTurnos->close();
$stmtUser = $conn->prepare("DELETE FROM Usuarios WHERE id_paciente = ?");
$stmtUser->bind_param("i", $id);
$stmtUser->execute();
$stmtUser->close();
$stmtPaciente = $conn->prepare("DELETE FROM Pacientes WHERE id = ?");
$stmtPaciente->bind_param("i", $id);

if ($stmtPaciente->execute()) {
    header("Location: lista.php?eliminado=1&turnos=$turnosEliminados");
} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
    </head>
    <body class='bg-light'>
        <div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>Error al eliminar paciente</h4>
                <p>" . htmlspecialchars($conn->error) . "</p>
                <a href='lista.php' class='btn btn-primary'>Volver al listado</a>
            </div>
        </div>
    </body>
    </html>";
}
$stmtPaciente->close();
?>