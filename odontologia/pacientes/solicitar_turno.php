<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['id_paciente'])) {
    header("Location: ../pacientes/registro.php");
    exit();
}

$id_paciente = $_SESSION['id_paciente'];

$stmt = $conn->prepare("SELECT id, nombre, apellido FROM Pacientes WHERE id = ?");
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$paciente = $stmt->get_result()->fetch_assoc();

if (!$paciente) {
    echo "Paciente no encontrado.";
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_turno = $_POST['fecha_turno'] ?? '';
    $hora_turno = $_POST['hora_turno'] ?? '';
    $motivo = trim($_POST['motivo'] ?? '');

    if (!$fecha_turno || !$hora_turno || !$motivo) {
        $error = "Por favor completa todos los campos.";
    } elseif (strtotime($fecha_turno) < strtotime(date('Y-m-d'))) {
        $error = "La fecha del turno no puede ser en el pasado.";
    } else {
        $stmt = $conn->prepare("INSERT INTO Turnos (id_pacientes, fecha_turno, hora_turno, motivo, estado) VALUES (?, ?, ?, ?, 'Pendiente')");
        $stmt->bind_param("isss", $id_paciente, $fecha_turno, $hora_turno, $motivo);

        if ($stmt->execute()) {
            $success = "Turno solicitado correctamente.";
        } else {
            $error = "Error al solicitar el turno. Inténtalo de nuevo.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Solicitar Turno</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container mt-3 d-flex justify-content-end">
  <form action="logout.php" method="POST">
    <button type="submit" class="btn btn-danger btn-sm">Cerrar sesión</button>
  </form>
</div>

<div class="container mt-5" style="max-width: 600px;">
  <h2 class="mb-4 text-center">Solicitar Nuevo Turno</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php elseif ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="fecha_turno" class="form-label">Fecha del turno</label>
      <input type="date" class="form-control" id="fecha_turno" name="fecha_turno" required
        min="<?= date('Y-m-d') ?>" value="<?= isset($_POST['fecha_turno']) ? htmlspecialchars($_POST['fecha_turno']) : '' ?>">
    </div>

    <div class="mb-3">
      <label for="hora_turno" class="form-label">Hora del turno</label>
      <input type="time" class="form-control" id="hora_turno" name="hora_turno" required
        value="<?= isset($_POST['hora_turno']) ? htmlspecialchars($_POST['hora_turno']) : '' ?>">
    </div>

    <div class="mb-3">
      <label for="motivo" class="form-label">Motivo</label>
      <textarea class="form-control" id="motivo" name="motivo" rows="3" required><?= isset($_POST['motivo']) ? htmlspecialchars($_POST['motivo']) : '' ?></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100">Solicitar Turno</button>
  </form>

  <div class="text-center mt-4">
    <a href="mis_turnos.php" class="btn btn-secondary">Volver a Mis Turnos</a>
  </div>
</div>

</body>
</html>
