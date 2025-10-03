<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "DELETE FROM Tratamientos WHERE id = $id";

if ($conn->query($sql)) {
    header("Location: lista.php");
} else {
    echo "Error al eliminar tratamiento: " . $conn->error;
}
?>
