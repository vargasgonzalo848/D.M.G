<?php
include("../includes/header.php");
include("../includes/db.php");

$errores = [];
$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente'] ?? '';
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fecha_inicio = $_POST['fecha_inicio'] ?? '';
    $fecha_fin = $_POST['fecha_fin'] ?? null;
    $observaciones = trim($_POST['observaciones'] ?? '');

    if (!$id_paciente) $errores[] = "Selecciona un paciente";
    if (!$descripcion) $errores[] = "La descripción es obligatoria";
    if (!$fecha_inicio) $errores[] = "Selecciona fecha de inicio";

    if (empty($errores)) {
        $stmt = $conn->prepare("INSERT INTO Tratamientos (id_pacientes, descripcion, fecha_inicio, fecha_fin, observaciones) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $id_paciente, $descripcion, $fecha_inicio, $fecha_fin, $observaciones);
        if ($stmt->execute()) {
            $mensaje = "Tratamiento creado con éxito";
        } else {
            $errores[] = "Error al guardar tratamiento: " . $conn->error;
        }
        $stmt->close();
    }
}

$pacientes = $conn->query("SELECT id, nombre, apellido FROM Pacientes ORDER BY nombre");

?>

<h3 class="mb-3">Crear Nuevo Tratamiento</h3>

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
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required><?= $_POST['descripcion'] ?? '' ?></textarea>
  </div>
  <div class="mb-3">
    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= $_POST['fecha_inicio'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="fecha_fin" class="form-label">Fecha de Fin</label>
    <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?= $_POST['fecha_fin'] ?? '' ?>">
  </div>
  <div class="mb-3">
    <label for="observaciones" class="form-label">Observaciones</label>
    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= $_POST['observaciones'] ?? '' ?></textarea>
  </div>

  <button type="submit" class="btn btn-primary">Guardar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include("../includes/footer.php"); ?>
