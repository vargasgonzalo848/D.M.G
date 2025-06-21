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

$sql = "DELETE FROM Pacientes WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: lista.php");
} else {
    echo "Error al eliminar paciente: " . $conn->error;
}
?>
