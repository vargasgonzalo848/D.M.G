<?php
session_start();

if (isset($_SESSION['paciente_email'])) {
    header("Location: panel_paciente.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Bienvenido al Portal del Paciente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    body {
      background: #eef2f7;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .container {
      max-width: 450px;
      width: 100%;
      background: white;
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.12);
      text-align: center;
    }
    h1 {
      margin-bottom: 20px;
      color: #343a40;
      font-size: clamp(1.5rem, 4vw, 2rem);
    }
    p {
      font-size: clamp(0.9rem, 2.5vw, 1.1rem);
      margin-bottom: 25px;
    }
    .btn-social {
      margin-bottom: 15px;
      width: 100%;
      font-size: 1.05rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .btn-social i {
      margin-right: 8px;
      font-size: 1.2rem;
    }
    .btn-google {
      background-color: #dd4b39;
      color: white;
    }
    .btn-facebook {
      background-color: #3b5998;
      color: white;
    }
    .divider {
      margin: 25px 0;
      font-weight: bold;
      color: #888;
    }
    a.btn {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    a.btn i {
      margin-right: 8px;
    }
    @media (min-width: 576px) {
      .container {
        padding: 2.5rem;
      }
    }
    @media (min-width: 768px) {
      .container {
        padding: 3rem;
      }
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Bienvenido al Portal del Paciente</h1>
    <p>Para pedir un turno o consultar tus citas, regístrate o inicia sesión.</p>

    <button class="btn btn-google btn-social" onclick="alert('Aquí iría la integración con Google');">
      <i class="bi bi-google"></i> Registrarse con Google
    </button>

    <button class="btn btn-facebook btn-social" onclick="alert('Aquí iría la integración con Facebook');">
      <i class="bi bi-facebook"></i> Registrarse con Facebook
    </button>

    <div class="divider">o</div>

    <a href="/odontologia/pacientes/registro.php" class="btn btn-primary btn-social">
      <i class="bi bi-envelope-fill"></i> Registrarse con correo
    </a>
    <a href="login.php" class="btn btn-outline-primary btn-social mt-2">
      <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
    </a>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
