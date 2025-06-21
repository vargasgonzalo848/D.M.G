<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

$stmt = $conn->prepare("
    SELECT u.rol, p.id AS paciente_id, p.nombre, p.apellido, p.email, p.telefono
    FROM usuarios u
    LEFT JOIN pacientes p ON u.id_paciente = p.id
    WHERE u.nombre_usuario = ?
");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$data = $result->fetch_assoc();

if ($data['rol'] !== 'paciente' || $data['paciente_id'] === null) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$pacienteId = $data['paciente_id'];

$stmt_turnos = $conn->prepare("SELECT * FROM turnos WHERE id_pacientes = ? ORDER BY fecha_turno DESC, hora_turno DESC");
$stmt_turnos->bind_param("i", $pacienteId);
$stmt_turnos->execute();
$resultadoTurnos = $stmt_turnos->get_result();

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel del Paciente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background: #f4f7fc;
      font-family: 'Segoe UI', sans-serif;
    }
    .card {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .header {
      background: #0d6efd;
      color: white;
      padding: 20px;
      border-radius: 8px 8px 0 0;
    }
    .btn-turno {
      margin-top: 15px;
    }
  </style>
</head>
<body>

<div class="container mt-5">
  <div class="card">
    <div class="header">
      <h3>Hola, <?= htmlspecialchars($data['nombre'] . " " . $data['apellido']) ?> 👋</h3>
      <p><?= htmlspecialchars($data['email']) ?> | Tel: <?= htmlspecialchars($data['telefono']) ?></p>
    </div>
    <div class="card-body">
      <h5 class="card-title mb-4">📅 Tus Turnos</h5>

      <?php if ($resultadoTurnos->num_rows > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center">
            <thead class="table-light">
              <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <?php while ($turno = $resultadoTurnos->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($turno['fecha_turno']) ?></td>
                  <td><?= htmlspecialchars($turno['hora_turno']) ?></td>
                  <td><?= htmlspecialchars($turno['motivo']) ?></td>
                  <td>
                    <?php
                      switch ($turno['estado']) {
                        case 'pendiente': echo '<span class="badge bg-warning">Pendiente</span>'; break;
                        case 'confirmado': echo '<span class="badge bg-success">Confirmado</span>'; break;
                        case 'cancelado': echo '<span class="badge bg-danger">Cancelado</span>'; break;
                        default: echo htmlspecialchars($turno['estado']);
                      }
                    ?>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p class="text-muted">No tienes turnos registrados aún.</p>
      <?php endif; ?>

      <a href="/odontologia/pacientes/solicitar_turno.php" class="btn btn-primary btn-turno">📆 Pedir un nuevo turno</a>
      <a href="/odontologia/logout.php" class="btn btn-outline-danger btn-turno">Cerrar sesión</a>
    </div>
  </div>
</div>

</body>
</html>
