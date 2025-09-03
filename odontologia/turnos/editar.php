<?php
include("../includes/header.php");
include("../includes/db.php");

if (!isset($_GET['id'])) {
    header("Location: lista.php");
    exit();
}

$id = intval($_GET['id']);
$errores = [];
$mensaje = '';

$sql = "SELECT * FROM Turnos WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: lista.php");
    exit();
}

$turno = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? '';
    $fecha_turno = $_POST['fecha_turno'] ?? '';
    $hora_turno = $_POST['hora_turno'] ?? '';
    $motivo = $_POST['motivo'] ?? '';
    $estado = $_POST['estado'] ?? 'pendiente';

    if (!$id_paciente) $errores[] = "Selecciona un paciente";
    if (!$fecha_turno) $errores[] = "Selecciona fecha";
    if (!$hora_turno) $errores[] = "Selecciona hora";

    if (empty($errores)) {
        $stmt = $conn->prepare("UPDATE Turnos SET id_pacientes = ?, fecha_turno = ?, hora_turno = ?, motivo = ?, estado = ? WHERE id = ?");
        $stmt->bind_param("issssi", $id_paciente, $fecha_turno, $hora_turno, $motivo, $estado, $id);
        if ($stmt->execute()) {
            $mensaje = "Turno actualizado correctamente";

            $turno['id_pacientes'] = $id_paciente;
            $turno['fecha_turno'] = $fecha_turno;
            $turno['hora_turno'] = $hora_turno;
            $turno['motivo'] = $motivo;
            $turno['estado'] = $estado;
        } else {
            $errores[] = "Error al actualizar turno: " . $conn->error;
        }
        $stmt->close();
    }
}

$pacientes = $conn->query("SELECT id, nombre, apellido FROM Pacientes ORDER BY nombre");
?>

<h3 class="mb-3">Editar Turno</h3>

<?php if ($mensaje): ?>
    <div class="alert alert-success"><?= $mensaje ?></div>
<?php endif; ?>

<?php if ($errores): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
  <div class="mb-3">
    <label for="id_paciente" class="form-label">Paciente</label>
    <select class="form-select" name="id_paciente" id="id_paciente" required>
      <option value="">-- Selecciona un paciente --</option>
      <?php while($fila = $pacientes->fetch_assoc()): ?>
        <option value="<?= $fila['id'] ?>" <?= ($turno['id_pacientes'] == $fila['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($fila['nombre'] . " " . $fila['apellido']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="mb-3">
    <label for="fecha_turno" class="form-label">Fecha</label>
    <input type="date" class="form-control" id="fecha_turno" name="fecha_turno" value="<?= $turno['fecha_turno'] ?>" required>
  </div>
  <div class="mb-3">
    <label for="hora_turno" class="form-label">Hora</label>
    <input type="time" class="form-control" id="hora_turno" name="hora_turno" value="<?= substr($turno['hora_turno'], 0, 5) ?>" required>
  </div>
  <div class="mb-3">
    <label for="motivo" class="form-label">Motivo</label>
    <textarea class="form-control" id="motivo" name="motivo" rows="3"><?= htmlspecialchars($turno['motivo']) ?></textarea>
  </div>
  <div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select class="form-select" name="estado" id="estado">
      <option value="pendiente" <?= ($turno['estado'] == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
      <option value="confirmado" <?= ($turno['estado'] == 'confirmado') ? 'selected' : '' ?>>Confirmado</option>
      <option value="cancelado" <?= ($turno['estado'] == 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Actualizar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include("../includes/footer.php"); ?>
