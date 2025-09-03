<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logoLink = '/odontologia/index.php';
if (isset($_SESSION['rol'])) {
    if ($_SESSION['rol'] === 'admin') {
        $logoLink = '/odontologia/dashboard.php';
    } elseif ($_SESSION['rol'] === 'paciente') {
        $logoLink = '/odontologia/panel_paciente.php';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Odontología</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= $logoLink ?>">Odontología</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarOpciones" aria-controls="navbarOpciones" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarOpciones">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') : ?>
            <li class="nav-item">
              <a class="nav-link" href="/odontologia/dashboard.php">Ver Panel</a>
            </li>
          <?php endif; ?>

          <li class="nav-item">
            <a class="nav-link" href="/odontologia/servicios.php">Servicios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/odontologia/sobre_nosotros.php">Sobre nosotros</a>
          </li>

          <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'paciente') : ?>
            <li class="nav-item">
              <a class="nav-link btn btn-success text-white ms-2" href="pacientes/solicitar_turno.php">
                Pedir Turno
              </a>
            </li>
          <?php endif; ?>
        </ul>

        <div class="d-flex align-items-center">
          <span class="navbar-text me-3">👤 <?= $_SESSION['usuario'] ?? '' ?></span>
          <a href="/odontologia/logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
        </div>
      </div>
    </div>
  </nav>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
