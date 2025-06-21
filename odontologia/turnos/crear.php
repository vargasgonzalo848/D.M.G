<?php
include("../includes/header.php");
include("../includes/db.php");

$errores = [];
$mensaje = '';

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
        $stmt = $conn->prepare("INSERT INTO Turnos (id_pacientes, fecha_turno, hora_turno, motivo, estado) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_paciente, $fecha_turno, $hora_turno, $motivo, $estado);
        if ($stmt->execute()) {
            $mensaje = "Turno creado con éxito";
        } else {
            $errores[] = "Error al guardar turno: " . $conn->error;
        }
        $stmt->close();
    }
}

$pacientes = $conn->query("SELECT id, nombre, apellido FROM Pacientes ORDER BY nombre");

?>

<h3 class="mb-3">Crear Nuevo Turno</h3>

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
        <option value="<?= $fila['id'] ?>" <?= (isset($_POST['id_paciente']) && $_POST['id_paciente'] == $fila['id']) ? 'selected' : '' ?>>
          <?= htmlspecialchars($fila['nombre'] . " " . $fila['apellido']) ?>
        </option>
      <?php endwhile; ?>
    </select>
  </div>
  <div class="mb-3">
    <label for="fecha_turno" class="form-label">Fecha</label>
    <input type="date" class="form-control" id="fecha_turno" name="fecha_turno" value="<?= $_POST['fecha_turno'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="hora_turno" class="form-label">Hora</label>
    <input type="time" class="form-control" id="hora_turno" name="hora_turno" value="<?= $_POST['hora_turno'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="motivo" class="form-label">Motivo</label>
    <textarea class="form-control" id="motivo" name="motivo" rows="3"><?= $_POST['motivo'] ?? '' ?></textarea>
  </div>
  <div class="mb-3">
    <label for="estado" class="form-label">Estado</label>
    <select class="form-select" name="estado" id="estado">
      <option value="pendiente" <?= (($_POST['estado'] ?? '') == 'pendiente') ? 'selected' : '' ?>>Pendiente</option>
      <option value="confirmado" <?= (($_POST['estado'] ?? '') == 'confirmado') ? 'selected' : '' ?>>Confirmado</option>
      <option value="cancelado" <?= (($_POST['estado'] ?? '') == 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
    </select>
  </div>

  <button type="submit" class="btn btn-primary">Guardar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include("../includes/footer.php"); ?>
