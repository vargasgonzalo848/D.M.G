<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

$id = intval($_GET['id']);

$sqlCheckTratamientos = "SELECT COUNT(*) as total FROM Tratamientos WHERE id_pacientes = $id";
$sqlCheckTurnos = "SELECT COUNT(*) as total FROM Turnos WHERE id_pacientes = $id";

$resultTratamientos = $conn->query($sqlCheckTratamientos);
$resultTurnos = $conn->query($sqlCheckTurnos);

$tratamientos = $resultTratamientos->fetch_assoc()['total'];
$turnos = $resultTurnos->fetch_assoc()['total'];

if ($tratamientos > 0 || $turnos > 0) {
    echo "No se puede eliminar paciente, tiene tratamientos o turnos asignados.";
    echo "<br><a href='lista.php'>Volver al listado</a>";
    exit();
}

$stmtUser = $conn->prepare("DELETE FROM Usuarios WHERE id_paciente = ?");
$stmtUser->bind_param("i", $id);
$stmtUser->execute();
$stmtUser->close();

$stmtPaciente = $conn->prepare("DELETE FROM Pacientes WHERE id = ?");
$stmtPaciente->bind_param("i", $id);

if ($stmtPaciente->execute()) {
    header("Location: lista.php");
} else {
    echo "Error al eliminar paciente: " . $conn->error;
}
$stmtPaciente->close();
?>
