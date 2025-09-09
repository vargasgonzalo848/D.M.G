<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header.php");

$totalPacientes = $conn->query("SELECT COUNT(*) AS total FROM pacientes")->fetch_assoc()['total'];
$totalDentistas = $conn->query("SELECT COUNT(*) AS total FROM dentistas")->fetch_assoc()['total'];
$turnosHoy = $conn->query("SELECT COUNT(*) AS total FROM turnos WHERE fecha_turno = CURDATE()")->fetch_assoc()['total'];
$tratamientosActivos = $conn->query("SELECT COUNT(*) AS total FROM tratamientos WHERE fecha_fin IS NULL OR fecha_fin > CURDATE()")->fetch_assoc()['total'];

$turnos = $conn->query("
    SELECT 
        t.hora_turno,
        p.nombre AS nombre_paciente,
        p.apellido AS apellido_paciente,
        p.cedula,
        tr.descripcion AS descripcion_tratamiento
    FROM turnos t
    INNER JOIN pacientes p ON t.id_pacientes = p.id
    LEFT JOIN tratamientos tr ON t.id_pacientes = tr.id_pacientes
        AND (tr.fecha_fin IS NULL OR tr.fecha_fin > CURDATE())
    WHERE t.fecha_turno = CURDATE()
    ORDER BY t.hora_turno ASC
");
?>
<link rel="stylesheet" href="css/dashboard.css">

<div class="container mt-5">
  <h2 class="mb-4 text-center text-primary">📊 Panel de Control</h2>

  <div class="row g-4 justify-content-center">
    <div class="col-md-3">
      <a href="pacientes/lista.php" class="text-decoration-none">
        <div class="card text-white bg-primary h-100 text-center">
          <div class="card-body">
            <div class="icon-circle mb-3 mx-auto">
              <i class="bi bi-people text-primary fs-4"></i>
            </div>
            <h5 class="card-title">Pacientes</h5>
            <p class="display-6 fw-bold"><?= $totalPacientes ?></p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="dentistas/lista.php" class="text-decoration-none">
        <div class="card text-white bg-success h-100 text-center">
          <div class="card-body">
            <div class="icon-circle mb-3 mx-auto">
              <i class="bi bi-person-badge text-success fs-4"></i>
            </div>
            <h5 class="card-title">Dentistas</h5>
            <p class="display-6 fw-bold"><?= $totalDentistas ?></p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="turnos/lista.php" class="text-decoration-none">
        <div class="card text-white bg-warning h-100 text-center">
          <div class="card-body">
            <div class="icon-circle mb-3 mx-auto">
              <i class="bi bi-calendar-event text-warning fs-4"></i>
            </div>
            <h5 class="card-title">Turnos Hoy</h5>
            <p class="display-6 fw-bold"><?= $turnosHoy ?></p>
          </div>
        </div>
      </a>
    </div>

    <div class="col-md-3">
      <a href="tratamientos/lista.php" class="text-decoration-none">
        <div class="card text-white bg-danger h-100 text-center">
          <div class="card-body">
            <div class="icon-circle mb-3 mx-auto">
              <i class="bi bi-heart-pulse text-danger fs-4"></i>
            </div>
            <h5 class="card-title">Tratamientos Activos</h5>
            <p class="display-6 fw-bold"><?= $tratamientosActivos ?></p>
          </div>
        </div>
      </a>
    </div>
  </div>

  <div class="mt-5">
    <h4 class="text-center text-secondary mb-4">📋 Turnos de Hoy</h4>
    
    <div class="table-responsive">
      <table class="table table-striped table-bordered text-center">
        <thead class="table-primary">
          <tr>
            <th>Hora</th>
            <th>Paciente</th>
            <th>Cédula</th>
            <th>Tratamiento</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($turnos && $turnos->num_rows > 0): ?>
            <?php while($row = $turnos->fetch_assoc()): ?>
              <tr>
                <td><?= $row['hora_turno'] ?></td>
                <td><?= $row['nombre_paciente'] . ' ' . $row['apellido_paciente'] ?></td>
                <td><?= $row['cedula'] ?></td>
                <td><?= $row['descripcion_tratamiento'] ?? 'Sin tratamiento' ?></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay turnos hoy</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
