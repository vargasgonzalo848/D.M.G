<?php
include("../includes/header.php");
include("../includes/db.php");

$sql = "SELECT t.*, p.nombre AS nombre_paciente, p.apellido AS apellido_paciente
        FROM Turnos t
        JOIN Pacientes p ON t.id_pacientes = p.id
        ORDER BY t.fecha_turno DESC, t.hora_turno DESC";
$resultado = $conn->query($sql);

$hoy = date("Y-m-d");
?>

<h3 class="mb-3">Listado de Turnos</h3>

<a href="crear.php" class="btn btn-success mb-3">+ Agregar Turno</a>

<table class="table table-striped table-bordered">
  <thead class="table-primary">
    <tr>
      <th>ID</th>
      <th>Paciente</th>
      <th>Fecha</th>
      <th>Hora</th>
      <th>Motivo</th>
      <th>Estado</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($resultado->num_rows > 0): ?>
        <?php while ($fila = $resultado->fetch_assoc()): ?>
            <?php
                $esHoy = ($fila['fecha_turno'] === $hoy);
                $claseFila = $esHoy ? 'table-warning fw-bold' : '';
            ?>
            <tr class="<?= $claseFila ?>">
                <td><?= $fila['id'] ?></td>
                <td>
                    <?= htmlspecialchars($fila['nombre_paciente'] . " " . $fila['apellido_paciente']) ?>
                    <?php if ($esHoy): ?>
                        <span class="badge bg-info text-dark ms-2">HOY</span>
                    <?php endif; ?>
                </td>
                <td><?= $fila['fecha_turno'] ?></td>
                <td><?= substr($fila['hora_turno'], 0, 5) ?></td>
                <td><?= htmlspecialchars($fila['motivo']) ?></td>
                <td><?= ucfirst($fila['estado']) ?></td>
                <td>
                  <a href="editar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                  <a href="eliminar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar turno?')">Eliminar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7" class="text-center">No hay turnos registrados</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php include("../includes/footer.php"); ?>
