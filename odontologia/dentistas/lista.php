<?php
include("../includes/header.php");
include("../includes/db.php");

$sql = "SELECT * FROM Dentistas ORDER BY apellido, nombre";
$resultado = $conn->query($sql);
?>

<h3 class="mb-3">Listado de Dentistas</h3>

<a href="crear.php" class="btn btn-success mb-3">+ Agregar Dentista</a>

<table class="table table-striped table-bordered">
  <thead class="table-primary">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Apellido</th>
      <th>Email</th>
      <th>Teléfono</th>
      <th>Fecha de Nacimiento</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= $fila['id'] ?></td>
          <td><?= htmlspecialchars($fila['nombre']) ?></td>
          <td><?= htmlspecialchars($fila['apellido']) ?></td>
          <td><?= htmlspecialchars($fila['email']) ?></td>
          <td><?= htmlspecialchars($fila['telefono']) ?></td>
          <td><?= $fila['fecha_nacimiento'] ?></td>
          <td>
            <a href="editar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="eliminar.php?id=<?= $fila['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar dentista?')">Eliminar</a>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="7" class="text-center">No hay dentistas registrados</td></tr>
    <?php endif; ?>
  </tbody>
</table>

<?php include("../includes/footer.php"); ?>
