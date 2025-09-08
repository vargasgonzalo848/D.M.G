<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['id_paciente'])) {
    header("Location: ../registro.php");
    exit();
}

$id_paciente = $_SESSION['id_paciente'];

$stmt_paciente = $conn->prepare("SELECT nombre, apellido FROM Pacientes WHERE id = ?");
$stmt_paciente->bind_param("i", $id_paciente);
$stmt_paciente->execute();
$paciente = $stmt_paciente->get_result()->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit();
}

$sql = "SELECT * FROM Turnos WHERE id_pacientes = ? ORDER BY fecha_turno DESC, hora_turno DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Mis Turnos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-3 d-flex justify-content-end">
  <form action="logout.php" method="POST">
    <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
  </form>
</div>

<div class="container mt-3">
  <h2 class="mb-4 text-center">Turnos de <?= htmlspecialchars($paciente['nombre'] . " " . $paciente['apellido']) ?></h2>

  <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered mx-auto" style="max-width: 700px;">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Hora</th>
          <th>Motivo</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($turno = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($turno['fecha_turno']) ?></td>
            <td><?= htmlspecialchars($turno['hora_turno']) ?></td>
            <td><?= htmlspecialchars($turno['motivo']) ?></td>
            <td><?= isset($turno['estado']) ? htmlspecialchars($turno['estado']) : 'Pendiente' ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p class="text-center">No tienes turnos solicitados.</p>
  <?php endif; ?>

  <div class="text-center mt-4">
    <a href="solicitar_turno.php" class="btn btn-primary">Solicitar Nuevo Turno</a>
  </div>
</div>

</body>
</html>
