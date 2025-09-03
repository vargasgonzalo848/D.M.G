<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include("includes/db.php");
include("includes/header.php");

$totalPacientes = $conn->query("SELECT COUNT(*) AS total FROM Pacientes")->fetch_assoc()['total'];
$totalDentistas = $conn->query("SELECT COUNT(*) AS total FROM Dentistas")->fetch_assoc()['total'];
$turnosHoy = $conn->query("SELECT COUNT(*) AS total FROM Turnos WHERE fecha_turno = CURDATE()")->fetch_assoc()['total'];
$tratamientosActivos = $conn->query("SELECT COUNT(*) AS total FROM Tratamientos WHERE fecha_fin IS NULL OR fecha_fin > CURDATE()")->fetch_assoc()['total'];

$turnosPorHora = [];
$horas = [];
for ($i = 0; $i < 24; $i++) {
    $horaInicio = sprintf('%02d:00:00', $i);
    $horaFin = sprintf('%02d:59:59', $i);
    $resultado = $conn->query("
        SELECT COUNT(*) AS total 
        FROM Turnos 
        WHERE CONCAT(fecha_turno, ' ', hora_turno) >= CONCAT(CURDATE(),' ','$horaInicio') 
          AND CONCAT(fecha_turno, ' ', hora_turno) <= CONCAT(CURDATE(),' ','$horaFin')
    ");
    $cantidad = $resultado->fetch_assoc()['total'];
    $horas[] = $i . ":00";
    $turnosPorHora[] = $cantidad;
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.card:hover {
  transform: scale(1.03);
  transition: 0.3s ease-in-out;
  box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.icon-circle {
  width: 50px;
  height: 50px;
  background: rgba(255,255,255,0.8);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
}
a.text-decoration-none:hover {
  text-decoration: none;
}
</style>

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
    <h4 class="text-center text-secondary mb-4">📈 Turnos en las próximas 24 horas</h4>
    <canvas id="graficoTurnos"></canvas>
  </div>
</div>

<script>
const ctx = document.getElementById('graficoTurnos').getContext('2d');
const grafico = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($horas) ?>,
        datasets: [{
            label: 'Turnos por Hora',
            data: <?= json_encode($turnosPorHora) ?>,
            fill: true,
            backgroundColor: 'rgba(13, 110, 253, 0.2)',
            borderColor: 'rgba(13, 110, 253, 1)',
            borderWidth: 2,
            pointBackgroundColor: 'rgba(13, 110, 253, 1)',
            tension: 0.3
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<?php include("includes/footer.php"); ?>
