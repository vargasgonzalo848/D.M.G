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

function hora_valida($hora, $fecha) {
    $hora_min = strtotime($hora);
    $minutos = date('i', $hora_min);
    if (!in_array($minutos, ['00','30'])) return false;

    $dia_semana = date('N', strtotime($fecha));
    $hora_num = (int)date('H', $hora_min);
    $min_num = (int)date('i', $hora_min);

    if ($dia_semana >= 1 && $dia_semana <= 5) {
        $inicio = 8; $fin = 21;
    } elseif ($dia_semana == 6) {
        $inicio = 8; $fin = 16;
    } else {
        return false;
    }

    if ($hora_num < $inicio || $hora_num > $fin || ($hora_num == $fin && $min_num > 0)) return false;

    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fecha_turno = $_POST['fecha_turno'] ?? '';
    $hora_turno = $_POST['hora_turno'] ?? '';
    $motivo = trim($_POST['motivo'] ?? '');

    if (!$fecha_turno || !$hora_turno || !$motivo) {
        $error = "Por favor completa todos los campos.";
    } elseif (strtotime($fecha_turno) < strtotime(date('Y-m-d'))) {
        $error = "No se puede seleccionar una fecha pasada.";
    } elseif (!hora_valida($hora_turno, $fecha_turno)) {
        $error = "El horario no es válido. Debe estar dentro de los horarios de atención y en intervalos de 30 minutos, sin domingos.";
    } else {
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS total FROM Turnos WHERE id_pacientes = ? AND fecha_turno = ? AND hora_turno = ?");
        $stmt_check->bind_param("iss", $id_paciente, $fecha_turno, $hora_turno);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result()->fetch_assoc();
        $stmt_check->close();

        if ($res_check['total'] > 0) {
            $error = "Ya existe un turno a esa fecha y hora para este paciente.";
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
        min="<?= date('Y-m-d', strtotime('tomorrow')) ?>">
    </div>

    <div class="mb-3">
      <label for="hora_turno" class="form-label">Hora del turno</label>
      <input type="time" class="form-control" id="hora_turno" name="hora_turno" required
        step="1800">
      <small class="text-muted">Horario disponible: Lun-Vie 08:00-21:00, Sábados 08:00-16:00. No domingos. Intervalos de 30 min.</small>
    </div>

    <div class="mb-3">
      <label for="motivo" class="form-label">Motivo</label>
      <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
    </div>

    <button type="submit" class="btn btn-primary w-100">Solicitar Turno</button>
  </form>

  <div class="text-center mt-4">
    <a href="../panel_paciente.php" class="btn btn-secondary">Volver al Panel</a>
  </div>
</div>

</body>
</html>
