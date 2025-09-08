<?php
include("../includes/header.php");
include("../includes/db.php");

$buscar = trim($_GET['buscar'] ?? '');

if ($buscar) {
    $sql = "SELECT tr.*, p.nombre AS nombre_paciente, p.apellido AS apellido_paciente
            FROM Tratamientos tr
            JOIN Pacientes p ON tr.id_pacientes = p.id
            WHERE p.nombre LIKE ? OR p.apellido LIKE ?
            ORDER BY tr.fecha_inicio DESC";
    $stmt = $conn->prepare($sql);
    $likeBuscar = "%$buscar%";
    $stmt->bind_param("ss", $likeBuscar, $likeBuscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql = "SELECT tr.*, p.nombre AS nombre_paciente, p.apellido AS apellido_paciente 
            FROM Tratamientos tr
            JOIN Pacientes p ON tr.id_pacientes = p.id
            ORDER BY tr.fecha_inicio DESC";
    $resultado = $conn->query($sql);
}
?>

<h3 class="mb-3">Listado de Tratamientos</h3>

<form method="get" class="mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" class="form-control" name="buscar" placeholder="Buscar por paciente" value="<?= htmlspecialchars($buscar) ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="lista.php" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

<a href="crear.php" class="btn btn-success mb-3">+ Agregar Tratamiento</a>

<table class="table table-striped table-bordered">
  <thead class="table-primary">
    <tr>
      <th>ID</th>
      <th>Paciente</th>
      <th>Descripción</th>
      <th>Fecha Inicio</th>
      <th>Fecha Fin</th>
      <th>Observaciones</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $fila['id'] ?></td>
          <td><?= htmlspecialchars($fila['nombre_paciente'] . " " . $fila['apellido_paciente']) ?></td>
          <td><?= htmlspecialchars($fila['descripcion']) ?></td>
          <td><?= $fila['fecha_inicio'] ?></td>
          <td><?= $fila['fecha_fin'] ?? '-' ?></td>
          <td><?= htmlspecialchars($fila['observaciones']) ?></td>
          <td>
            <a href="editar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="eliminar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar tratamiento?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center">No hay tratamientos registrados</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php include("../includes/footer.php"); ?>
