<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Odontología</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="/odontologia/index.php">Odontología</a>
      <a class="navbar-brand" href="/odontologia/dashboard.php">Ver Panel</a>
      <div>
        <span class="navbar-text me-3">👤 <?= $_SESSION['usuario'] ?? '' ?></span>
        <a href="/odontologia/logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
      </div>
    </div>
  </nav>
  <div class="container">
