<?php
session_start();
include("../includes/db.php");

if (!isset($_SESSION['usuario']) || !isset($_SESSION['id_paciente'])) {
    header("Location: login.php");
    exit();
}

$pacienteId = $_SESSION['id_paciente'];

// Obtener turno activo
$stmt = $conn->prepare("SELECT * FROM turnos WHERE id_pacientes = ? AND estado IN ('pendiente','confirmado') ORDER BY fecha_turno ASC, hora_turno ASC LIMIT 1");
$stmt->bind_param("i", $pacienteId);
$stmt->execute();
$result = $stmt->get_result();
$turno = $result->fetch_assoc();

if (!$turno) {
    $_SESSION['error'] = "No tienes un turno activo para modificar.";
    header("Location: index.php");
    exit();
}

// Función para validar hora
function hora_valida($hora, $fecha) {
    $hora_min = strtotime($hora);
    $minutos = date('i', $hora_min);
    if (!in_array($minutos, ['00','30'])) return false;

    $dia_semana = date('N', strtotime($fecha)); // 1=Lunes ... 7=Domingo
    $hora_num = (int)date('H', $hora_min);
    $min_num = (int)date('i', $hora_min);

    if ($dia_semana >= 1 && $dia_semana <= 5) { // Lun-Vie
        $inicio = 8; $fin = 21;
    } elseif ($dia_semana == 6) { // Sábado
        $inicio = 8; $fin = 16;
    } else { // Domingo
        return false;
    }

    if ($hora_num < $inicio || $hora_num > $fin || ($hora_num == $fin && $min_num > 0)) return false;

    return true;
}

// Manejo del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'] ?? '';
    $hora = $_POST['hora'] ?? '';
    $motivo = $_POST['motivo'] ?? '';
    $error = '';
    $success = '';

    if (empty($fecha) || empty($hora) || empty($motivo)) {
        $error = "Todos los campos son obligatorios.";
    } elseif (strtotime($fecha) < strtotime(date('Y-m-d'))) {
        $error = "No se puede seleccionar una fecha pasada.";
    } elseif (!hora_valida($hora, $fecha)) {
        $error = "La hora no es válida. Debe estar dentro del horario permitido y en intervalos de 30 minutos.";
    } else {
        // Verificar que no haya otro turno a la misma fecha y hora
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS total FROM turnos WHERE id_pacientes = ? AND fecha_turno = ? AND hora_turno = ? AND id != ?");
        $stmt_check->bind_param("issi", $pacienteId, $fecha, $hora, $turno['id']);
        $stmt_check->execute();
        $res_check = $stmt_check->get_result()->fetch_assoc();
        $stmt_check->close();

        if ($res_check['total'] > 0) {
            $error = "Ya existe un turno a esa fecha y hora para este paciente.";
        } else {
            $stmtUpdate = $conn->prepare("UPDATE turnos SET fecha_turno = ?, hora_turno = ?, motivo = ? WHERE id = ? AND id_pacientes = ?");
            $stmtUpdate->bind_param("sssii", $fecha, $hora, $motivo, $turno['id'], $pacienteId);
            if ($stmtUpdate->execute()) {
                $success = "Turno modificado correctamente.";
                $turno['fecha_turno'] = $fecha;
                $turno['hora_turno'] = $hora;
                $turno['motivo'] = $motivo;
            } else {
                $error = "Error al modificar el turno. Intenta nuevamente.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Turno</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f4f7fc; font-family: 'Segoe UI', sans-serif; }
    .card { max-width: 500px; margin: 40px auto; padding: 20px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); background: white; }
  </style>
</head>
<body>

<div class="card">
  <h4 class="mb-3 text-center">✏️ Modificar Turno</h4>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <div class="mb-3">
      <label for="fecha" class="form-label">Fecha del turno</label>
      <input type="date" id="fecha" name="fecha" class="form-control"
        min="<?= date('Y-m-d', strtotime('tomorrow')) ?>"
        value="<?= htmlspecialchars($turno['fecha_turno']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="hora" class="form-label">Hora del turno</label>
      <input type="time" id="hora" name="hora" class="form-control"
        step="1800"
        value="<?= htmlspecialchars($turno['hora_turno']) ?>" required>
      <small class="text-muted">Horario disponible: Lun-Vie 08:00-21:00, Sábados 08:00-16:00. No domingos. Intervalos de 30 min.</small>
    </div>

    <div class="mb-3">
      <label for="motivo" class="form-label">Motivo</label>
      <input type="text" id="motivo" name="motivo" class="form-control"
        value="<?= htmlspecialchars($turno['motivo']) ?>" required>
    </div>

    <div class="d-flex justify-content-between">
      <a href="../panel_paciente.php" class="btn btn-secondary">Volver</a>
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
