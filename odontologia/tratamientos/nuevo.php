<?php include("../includes/db.php"); ?>
<?php include("../includes/header.php"); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['id_paciente'];
    $descripcion = $_POST['descripcion'];
    $inicio = $_POST['fecha_inicio'];
    $fin = $_POST['fecha_fin'];
    $obs = $_POST['observaciones'];

    $stmt = $conn->prepare("INSERT INTO Tratamientos (id_pacientes, descripcion, fecha_inicio, fecha_fin, observaciones) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $id_paciente, $descripcion, $inicio, $fin, $obs);
    $stmt->execute();
    echo "<p>Tratamiento registrado correctamente.</p>";
}
?>

<h3>Nuevo Tratamiento</h3>
<form method="post">
  <label>Paciente:
    <select name="id_paciente" required>
      <option value="">-- Seleccionar --</option>
      <?php
        $pacientes = $conn->query("SELECT id, nombre, apellido FROM Pacientes");
        while ($p = $pacientes->fetch_assoc()) {
            echo "<option value='{$p['id']}'>{$p['nombre']} {$p['apellido']}</option>";
        }
      ?>
    </select>
  </label><br>

  <label>Descripción: <input type="text" name="descripcion" required></label><br>
  <label>Fecha Inicio: <input type="date" name="fecha_inicio"></label><br>
  <label>Fecha Fin: <input type="date" name="fecha_fin"></label><br>
  <label>Observaciones: <textarea name="observaciones"></textarea></label><br>

  <button type="submit">Guardar Tratamiento</button>
</form>

<?php include("../includes/footer.php"); ?>
