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

$sql = "SELECT * FROM Pacientes WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: lista.php");
    exit();
}

$paciente = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';

    if (!$nombre) $errores[] = "El nombre es obligatorio";
    if (!$apellido) $errores[] = "El apellido es obligatorio";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Email inválido";
    if (!$telefono) $errores[] = "El teléfono es obligatorio";
    if (!$fecha_nacimiento) $errores[] = "La fecha de nacimiento es obligatoria";

    if (empty($errores)) {
        $stmt = $conn->prepare("UPDATE Pacientes SET nombre = ?, apellido = ?, email = ?, telefono = ?, fecha_nacimiento = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nombre, $apellido, $email, $telefono, $fecha_nacimiento, $id);
        if ($stmt->execute()) {
            $mensaje = "Paciente actualizado correctamente";
            $paciente['nombre'] = $nombre;
            $paciente['apellido'] = $apellido;
            $paciente['email'] = $email;
            $paciente['telefono'] = $telefono;
            $paciente['fecha_nacimiento'] = $fecha_nacimiento;
        } else {
            $errores[] = "Error al actualizar paciente: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<h3 class="mb-3">Editar Paciente</h3>

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
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($paciente['nombre']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="apellido" class="form-label">Apellido</label>
    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($paciente['apellido']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($paciente['email']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($paciente['telefono']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $paciente['fecha_nacimiento'] ?>" required>
  </div>

  <button type="submit" class="btn btn-primary">Actualizar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include("../includes/footer.php"); ?>
