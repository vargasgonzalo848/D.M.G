<?php
session_start();
include("includes/db.php");

if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: index.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $stmt = $conn->prepare("SELECT * FROM Usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $fila = $resultado->fetch_assoc();
        $hash_db = $fila['contrasena'];
        $rol = $fila['rol'];
        $id_paciente = $fila['id_paciente'];

        if (password_verify($contrasena, $hash_db)) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $rol;
            
            if ($rol === 'paciente' && $id_paciente !== null) {
                $_SESSION['id_paciente'] = $id_paciente;
            }

            if ($rol === 'admin') {
                header("Location: index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Portal del Paciente</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="css/Registro_de_Usuario.css">
</head>
<body>

<div class="login-card">
  <h2>Iniciar Sesión</h2>

  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" autocomplete="off">
    <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
    <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
    <button type="submit" name="login" class="btn btn-primary w-100 mb-2">Entrar</button>
  </form>

  <button class="btn-forgot" data-bs-toggle="modal" data-bs-target="#forgotModal">
    Olvidé mi contraseña
  </button>

  <div class="divider"><span>¿No tienes cuenta?</span></div>

  <a href="/odontologia/pacientes/registro.php" class="btn btn-primary btn-social w-100">
    <i class="bi bi-envelope-fill"></i> Registrarse con Correo
  </a>
</div>

<div class="modal fade" id="forgotModal" tabindex="-1" aria-labelledby="forgotModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotModalLabel">Recuperar Contraseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p>Ingresa tu correo para recibir instrucciones de recuperación:</p>
        <input type="email" class="form-control" placeholder="Correo electrónico" id="recoverEmail">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="alert('Se enviaron instrucciones al correo');">Enviar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>