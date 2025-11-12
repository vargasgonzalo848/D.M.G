<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
$logoLink = '/odontologia/index.php'; 
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
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= $logoLink ?>">
      <a class="navbar-brand" href="<?php echo $logoLink; ?>">
  <span class="agrandar">
    <i class="bi bi-hospital me-2"></i>Odontología
  </span>
</a>
  <style>
  .agrandar {
    transition: transform 0.3s ease, color 0.3s ease;
    display: inline-block;
    font-weight: bold;

  }

  .agrandar:hover {
    transform: scale(1.3);
    color: #ecf1f6ff; 
  }
  </style>


        </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarOpciones" aria-controls="navbarOpciones" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarOpciones">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if (isset($_SESSION['rol'])): ?>
                    <?php if ($_SESSION['rol'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/odontologia/dashboard.php"><i class="bi bi-speedometer2 me-1"></i> Panel</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/odontologia/servicios.php"><i class="bi bi-tools me-1"></i> Servicios</a>
                    </li>

                    <?php if ($_SESSION['rol'] === 'paciente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/odontologia/panel_paciente.php">
                                <i class="bi bi-calendar-check me-1"></i> Mis Turnos
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <?php if (isset($_SESSION['usuario'])): ?>
                <div class="dropdown ms-auto">
                    <a class="btn btn-outline-light dropdown-toggle fw-semibold" href="#" 
                       role="button" id="menuUsuario" data-bs-toggle="dropdown" aria-expanded="false">
                        👤 <?= $_SESSION['usuario'] ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="menuUsuario">
                        <?php if ($_SESSION['rol'] === 'paciente'): ?>
                            <li>
                                <a class="dropdown-item" href="/odontologia/configuracion_perfil.php">
                                    <i class="bi bi-gear me-2"></i> Perfil
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a class="dropdown-item text-danger" href="/odontologia/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="/odontologia/Registro_de_Usuario.php" class="btn btn-light fw-semibold shadow-sm">
                    <i class="bi bi-person-plus"></i> Ingresar
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
