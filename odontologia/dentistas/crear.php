<?php
include("../includes/header.php");
include("../includes/db.php");

$errores = [];
$mensaje = '';

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
        $stmt = $conn->prepare("INSERT INTO Dentistas (nombre, apellido, email, telefono, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $fecha_nacimiento);
        if ($stmt->execute()) {
            $mensaje = "Dentista creado con éxito";
        } else {
            $errores[] = "Error al guardar dentista: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<h3 class="mb-3">Crear Nuevo Dentista</h3>

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
    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $_POST['nombre'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="apellido" class="form-label">Apellido</label>
    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= $_POST['apellido'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" value="<?= $_POST['telefono'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $_POST['fecha_nacimiento'] ?? '' ?>" required>
  </div>

  <button type="submit" class="btn btn-primary">Guardar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include("../includes/footer.php"); ?>
