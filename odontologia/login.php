<?php
session_start();
include("includes/db.php");

if (isset($_SESSION['usuario']) && isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
        header("Location: dashboard.php");
    } else {
        header("Location: servicios.php");
    }
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                header("Location: dashboard.php");
            } else {
                header("Location: servicios.php");
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
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #f8f9fa, #e0eafc);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
    }
    .login-card {
      max-width: 400px;
      width: 100%;
      padding: 2rem;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      background-color: white;
    }
    .form-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 1.2rem;
    }
    .form-control {
      padding-left: 2.5rem;
    }
    @media (min-width: 576px) {
      .login-card {
        padding: 2.5rem;
      }
    }
    @media (min-width: 768px) {
      .login-card {
        padding: 3rem;
      }
    }
  </style>
</head>
<body>

<div class="login-card">
  <h3 class="text-center mb-4 text-primary">Iniciar Sesión</h3>

  <?php if (!empty($error)) : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form method="POST" autocomplete="off">
    <div class="mb-3 position-relative">
      <i class="bi bi-person-fill form-icon"></i>
      <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
    </div>

    <div class="mb-3 position-relative">
      <i class="bi bi-lock-fill form-icon"></i>
      <input type="password" name="contrasena" class="form-control" placeholder="Contraseña" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Entrar</button>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

