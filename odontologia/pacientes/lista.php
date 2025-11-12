<?php
include("../includes/header.php");
include("../includes/db.php");

$buscar = trim($_GET['buscar'] ?? '');

if ($buscar) {
    $sql = "SELECT * FROM Pacientes 
            WHERE nombre LIKE ? OR cedula LIKE ?
            ORDER BY apellido, nombre";
    $stmt = $conn->prepare($sql);
    $likeBuscar = "%$buscar%";
    $stmt->bind_param("ss", $likeBuscar, $likeBuscar);
    $stmt->execute();
    $resultado = $stmt->get_result();
} else {
    $sql = "SELECT * FROM Pacientes ORDER BY apellido, nombre, cedula";
    $resultado = $conn->query($sql);
}
?>

<h3 class="mb-3">Listado de Pacientes</h3>

<form method="get" class="mb-3">
    <div class="input-group" style="max-width: 400px;">
        <input type="text" class="form-control" name="buscar" placeholder="Buscar por nombre o cédula" value="<?= htmlspecialchars($buscar) ?>">
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="lista.php" class="btn btn-secondary">Limpiar</a>
    </div>
</form>

<a href="crear.php" class="btn btn-success mb-3">+ Agregar Paciente</a>

<table class="table table-striped table-bordered">
  <thead class="table-primary">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Cédula</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Fecha de Nacimiento</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $fila['id'] ?></td>
          <td><?= htmlspecialchars($fila['nombre']) ?></td>
          <td><?= htmlspecialchars($fila['apellido']) ?></td>
          <td><?= htmlspecialchars($fila['cedula']) ?></td>
          <td><?= htmlspecialchars($fila['email']) ?></td>
          <td><?= htmlspecialchars($fila['telefono']) ?></td>
          <td><?= $fila['fecha_nacimiento'] ?></td>
          <td>
            <a href="editar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="eliminar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar paciente?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="8" class="text-center">No hay pacientes registrados</td></tr>
    <?php endif; ?>
  </tbody>
</table>

</div>

<?php include("../includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>