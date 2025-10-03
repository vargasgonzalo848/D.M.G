<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include("includes/header.php");
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
$_SESSION['id_paciente'] = $pacienteId;

$stmt_turno = $conn->prepare("SELECT * FROM turnos WHERE id_pacientes = ? AND estado IN ('pendiente','confirmado') ORDER BY fecha_turno ASC, hora_turno ASC LIMIT 1");
$stmt_turno->bind_param("i", $pacienteId);
$stmt_turno->execute();
$resultadoTurno = $stmt_turno->get_result();
$turnoActivo = $resultadoTurno->fetch_assoc();

$totalPendientes = $conn->query("SELECT COUNT(*) AS total FROM turnos WHERE id_pacientes = $pacienteId AND estado='pendiente'")->fetch_assoc()['total'];
$totalConfirmados = $conn->query("SELECT COUNT(*) AS total FROM turnos WHERE id_pacientes = $pacienteId AND estado='confirmado'")->fetch_assoc()['total'];
$totalCancelados = $conn->query("SELECT COUNT(*) AS total FROM turnos WHERE id_pacientes = $pacienteId AND estado='cancelado'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Panel del Paciente</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="css/panel_paciente.css">
</head>
<body>

<div class="container my-4">
  <div class="card">
    <div class="header">
      <h3>Hola, <?= htmlspecialchars($data['nombre'] . " " . $data['apellido']) ?> 👋</h3>
      <p><?= htmlspecialchars($data['email']) ?> | Tel: <?= htmlspecialchars($data['telefono']) ?></p>
    </div>
    <div class="card-body">

      <div class="row mb-4 text-center">
        <div class="col-md-3 mb-2">
          <div class="dashboard-card bg-primary">
            <h6>Próximo turno</h6>
            <p><?= $turnoActivo ? htmlspecialchars($turnoActivo['fecha_turno'] . " " . $turnoActivo['hora_turno']) : "No hay" ?></p>
          </div>
        </div>
        <div class="col-md-3 mb-2">
          <div class="dashboard-card bg-warning text-dark">
            <h6>Pendientes</h6>
            <p><?= $totalPendientes ?></p>
          </div>
        </div>
        <div class="col-md-3 mb-2">
          <div class="dashboard-card bg-success">
            <h6>Confirmados</h6>
            <p><?= $totalConfirmados ?></p>
          </div>
        </div>
        <div class="col-md-3 mb-2">
          <div class="dashboard-card bg-danger">
            <h6>Cancelados</h6>
            <p><?= $totalCancelados ?></p>
          </div>
        </div>
      </div>

      <h5 class="card-title mb-3">📅 Tu Turno</h5>

      <?php if ($turnoActivo): ?>
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
              <tr>
                <td><?= htmlspecialchars($turnoActivo['fecha_turno']) ?></td>
                <td><?= htmlspecialchars($turnoActivo['hora_turno']) ?></td>
                <td><?= htmlspecialchars($turnoActivo['motivo']) ?></td>
                <td>
                  <?php
                    switch ($turnoActivo['estado']) {
                      case 'pendiente': echo '<span class="badge bg-warning">Pendiente</span>'; break;
                      case 'confirmado': echo '<span class="badge bg-success">Confirmado</span>'; break;
                      default: echo htmlspecialchars($turnoActivo['estado']);
                    }
                  ?>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info text-center mt-4">
          No tienes turnos registrados aún.
        </div>
      <?php endif; ?>

      <div class="d-flex flex-column flex-md-row mt-3">
        <?php if ($turnoActivo && $turnoActivo['estado'] !== 'confirmado'): ?>
            <a href="/odontologia/pacientes/modificar_turno.php" class="btn btn-warning btn-turno">✏️ Modificar turno</a>
        <?php elseif (!$turnoActivo): ?>
            <a href="/odontologia/pacientes/solicitar_turno.php" class="btn btn-primary btn-turno">📆 Pedir turno</a>
        <?php endif; ?>
        <a href="/odontologia/logout.php" class="btn btn-outline-danger btn-turno">Cerrar sesión</a>
      </div>

      <div class="alert alert-light border-start border-4 border-primary mt-4">
        💡 Tip: Llega 10 minutos antes de tu turno y recuerda traer tu carnet de paciente.
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php include("includes/footer.php"); ?>
</body>
</html>
