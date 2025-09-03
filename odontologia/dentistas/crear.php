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

    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $rol = 'admin';

    if (!$nombre) $errores[] = "El nombre es obligatorio";
    if (!$apellido) $errores[] = "El apellido es obligatorio";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Email inválido";
    if (!$telefono) $errores[] = "El teléfono es obligatorio";
    if (!$fecha_nacimiento) $errores[] = "La fecha de nacimiento es obligatoria";

    if (!$nombre_usuario) $errores[] = "El nombre de usuario es obligatorio";
    if (!$contrasena) $errores[] = "La contraseña es obligatoria";

    if (empty($errores)) {
        $stmt = $conn->prepare("INSERT INTO Dentistas (nombre, apellido, email, telefono, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $fecha_nacimiento);

        if ($stmt->execute()) {
            $id_dentista = $stmt->insert_id;

            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
            $stmtUser = $conn->prepare("INSERT INTO Usuarios (nombre_usuario, contrasena, rol, id_dentista) VALUES (?, ?, ?, ?)");
            $stmtUser->bind_param("sssi", $nombre_usuario, $contrasena_hash, $rol, $id_dentista);

            if ($stmtUser->execute()) {
                $mensaje = "Dentista y usuario admin creados con éxito. Ahora puede ingresar con el nombre de usuario y contraseña.";
            } else {
                $errores[] = "Error al crear usuario: " . $conn->error;
            }
            $stmtUser->close();
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

  <hr>
  <h5>Datos de Usuario Admin</h5>

  <div class="mb-3">
    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?= $_POST['nombre_usuario'] ?? '' ?>" required>
  </div>
  <div class="mb-3">
    <label for="contrasena" class="form-label">Contraseña</label>
    <input type="password" class="form-control" id="contrasena" name="contrasena" required>
  </div>

  <button type="submit" class="btn btn-primary">Guardar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include("../includes/footer.php"); ?>
