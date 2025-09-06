<?php 
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
} 
$logoLink = '/odontologia/index.php'; 
if (isset($_SESSION['rol'])) { 
    if ($_SESSION['rol'] === 'admin') { 
        $logoLink = '/odontologia/dashboard.php'; 
    } elseif ($_SESSION['rol'] === 'paciente') { 
        $logoLink = '/odontologia/sobre_nosotros.php'; 
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
    <style>
        .btn-turnos {
            background: linear-gradient(135deg, #0D47A1, #1976D2); /* azul oscuro a azul medio */
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transition: transform 0.2s, box-shadow 0.2s, background 0.3s;
        }
        .btn-turnos:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, #1976D2, #0D47A1); /* inversión sutil de gradiente */
        }
        .btn-turnos i {
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= $logoLink ?>">
            <i class="bi bi-hospital me-2"></i>Odontología
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
                    <li class="nav-item">
                        <a class="nav-link" href="/odontologia/sobre_nosotros.php"><i class="bi bi-people me-1"></i> Sobre nosotros</a>
                    </li>
                    <?php if ($_SESSION['rol'] === 'paciente'): ?>
                        <li class="nav-item">
                            <a class="btn btn-turnos ms-2" href="/odontologia/panel_paciente.php">
                                <i class="bi bi-calendar-check me-1"></i> Mis Turnos
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center ms-auto">
                <?php if (isset($_SESSION['usuario'])): ?>
                    <span class="navbar-text me-3 fw-semibold">👤 <?= $_SESSION['usuario'] ?></span>
                    <a href="/odontologia/logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                    </a>
                <?php else: ?>
                    <a href="/odontologia/Registro_de_Usuario.php" class="btn btn-light fw-semibold shadow-sm">
                        <i class="bi bi-person-plus"></i> Crear Cuenta
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
