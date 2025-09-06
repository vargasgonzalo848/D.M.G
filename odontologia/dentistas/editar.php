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

$sql = "SELECT * FROM Dentistas WHERE id = $id";
$result = $conn->query($sql);
if ($result->num_rows == 0) {
    header("Location: lista.php");
    exit();
}
$dentista = $result->fetch_assoc();

$stmtUser = $conn->prepare("SELECT * FROM Usuarios WHERE id_dentista = ?");
$stmtUser->bind_param("i", $id);
$stmtUser->execute();
$resultUser = $stmtUser->get_result();
$usuario = $resultUser->fetch_assoc();
$stmtUser->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';

    $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (!$nombre) $errores[] = "El nombre es obligatorio";
    if (!$apellido) $errores[] = "El apellido es obligatorio";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = "Email inválido";
    if (!$telefono) $errores[] = "El teléfono es obligatorio";
    if (!$fecha_nacimiento) $errores[] = "La fecha de nacimiento es obligatoria";

    if (!$nombre_usuario) $errores[] = "El nombre de usuario es obligatorio";

    if (empty($errores)) {
        $stmt = $conn->prepare("UPDATE Dentistas SET nombre = ?, apellido = ?, email = ?, telefono = ?, fecha_nacimiento = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $nombre, $apellido, $email, $telefono, $fecha_nacimiento, $id);
        if ($stmt->execute()) {
            $mensaje = "Dentista actualizado correctamente";
            $dentista['nombre'] = $nombre;
            $dentista['apellido'] = $apellido;
            $dentista['email'] = $email;
            $dentista['telefono'] = $telefono;
            $dentista['fecha_nacimiento'] = $fecha_nacimiento;
        } else {
            $errores[] = "Error al actualizar dentista: " . $conn->error;
        }
        $stmt->close();

        if ($usuario) {
            if ($contrasena) {
                $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);
                $stmtUser = $conn->prepare("UPDATE Usuarios SET nombre_usuario = ?, contrasena = ? WHERE id_dentista = ?");
                $stmtUser->bind_param("ssi", $nombre_usuario, $contrasena_hash, $id);
            } else {
                $stmtUser = $conn->prepare("UPDATE Usuarios SET nombre_usuario = ? WHERE id_dentista = ?");
                $stmtUser->bind_param("si", $nombre_usuario, $id);
            }
            if (!$stmtUser->execute()) {
                $errores[] = "Error al actualizar usuario: " . $conn->error;
            }
            $stmtUser->close();
        }
    }
}
?>

<h3 class="mb-3">Editar Dentista</h3>

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
    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($dentista['nombre']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="apellido" class="form-label">Apellido</label>
    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($dentista['apellido']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($dentista['email']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="telefono" class="form-label">Teléfono</label>
    <input type="text" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($dentista['telefono']) ?>" required>
  </div>
  <div class="mb-3">
    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= $dentista['fecha_nacimiento'] ?>" required>
  </div>

  <hr>
  <h5>Datos de Usuario Admin</h5>

  <div class="mb-3">
    <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" value="<?= htmlspecialchars($usuario['nombre_usuario'] ?? '') ?>" required>
  </div>
  <div class="mb-3">
    <label for="contrasena" class="form-label">Contraseña (dejar vacío si no quieres cambiar)</label>
    <input type="password" class="form-control" id="contrasena" name="contrasena">
  </div>

  <button type="submit" class="btn btn-primary">Actualizar</button>
  <a href="lista.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include("../includes/footer.php"); ?>
