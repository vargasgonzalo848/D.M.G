<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

$id = intval($_GET['id']);


$sqlCheck = "SELECT COUNT(*) as total FROM Tratamientos WHERE id_pacientes = $id";
$resultCheck = $conn->query($sqlCheck);
$row = $resultCheck->fetch_assoc();

if ($row['total'] > 0) {
    // No eliminar, tiene tratamientos asignados
    echo "No se puede eliminar dentista, tiene tratamientos asignados.";
    echo "<br><a href='lista.php'>Volver al listado</a>";
    exit();
}

$sql = "DELETE FROM Dentistas WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: lista.php");
} else {
    echo "Error al eliminar dentista: " . $conn->error;
}
?>
