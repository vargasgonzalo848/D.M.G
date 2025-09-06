<?php
include("../includes/db.php");

if (!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

$id = intval($_GET['id']);
$conn->query("DELETE FROM Turnos WHERE id = $id");

header("Location: lista.php");
exit();
?>
