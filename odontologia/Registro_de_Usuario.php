<?php
session_start();
include("includes/db.php");

// Redirigir si ya está logueado
if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: sobre_nosotros.php");
    } else {
        header("Location: sobre_nosotros.php");
    }
    exit();
}

$error = "";

// Manejo de login
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

        if (password_verify($contrasena, $hash_db)) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $rol;

            if ($rol === 'admin') {
                header("Location: sobre_nosotros.php");
            } else {
                header("Location: sobre_nosotros.php");
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
<style>
  body {
    background: linear-gradient(135deg, #6c5ce7, #00b894);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .login-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.25);
    padding: 3rem 2.5rem;
    width: 100%;
    max-width: 420px;
    text-align: center;
    position: relative;
  }

  .login-card h2 {
    margin-bottom: 1.5rem;
    color: #343a40;
    font-weight: 700;
  }

  .form-control {
    border-radius: 50px;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
    transition: 0.3s;
  }

  .form-control:focus {
    box-shadow: 0 0 0 2px #6c5ce7;
    border-color: #6c5ce7;
  }

  .btn-primary {
    border-radius: 50px;
    padding: 0.75rem;
    font-weight: bold;
    transition: 0.3s;
  }

  .btn-primary:hover {
    background-color: #00b894;
    border-color: #00b894;
  }

  .btn-forgot {
    background: none;
    border: none;
    color: #6c5ce7;
    margin-bottom: 20px;
    cursor: pointer;
    font-weight: 500;
    text-decoration: underline;
    transition: 0.3s;
  }
  .btn-forgot:hover { color: #00b894; }

  .divider {
    margin: 20px 0;
    text-align: center;
    position: relative;
  }

  .divider::before, .divider::after {
    content: '';
    height: 1px;
    width: 40%;
    background: #ccc;
    position: absolute;
    top: 50%;
  }
  .divider::before { left: 0; }
  .divider::after { right: 0; }
  .divider span { padding: 0 10px; color: #888; font-weight: bold; }

  .btn-social {
    border-radius: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 10px;
    font-weight: 500;
    padding: 0.6rem;
    transition: 0.3s;
  }
  .btn-google { background-color: #dd4b39; color: white; }
  .btn-google:hover { background-color: #c43b2a; }
  .btn-facebook { background-color: #3b5998; color: white; }
  .btn-facebook:hover { background-color: #33497b; }

  .modal-header, .modal-footer { border: none; }
</style>
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

  <!-- Botón olvidé contraseña -->
  <button class="btn-forgot" data-bs-toggle="modal" data-bs-target="#forgotModal">
    Olvidé mi contraseña
  </button>

  <div class="divider"><span>o registrarse con</span></div>

  <button class="btn btn-google btn-social w-100" onclick="alert('Integración con Google');">
    <i class="bi bi-google"></i> Google
  </button>
  <button class="btn btn-facebook btn-social w-100" onclick="alert('Integración con Facebook');">
    <i class="bi bi-facebook"></i> Facebook
  </button>
  <a href="/odontologia/pacientes/registro.php" class="btn btn-primary btn-social w-100">
    <i class="bi bi-envelope-fill"></i> Correo
  </a>
</div>

<!-- Modal de Olvidé contraseña -->
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
