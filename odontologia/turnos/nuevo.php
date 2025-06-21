<?php include("../includes/db.php"); ?>
<?php include("../includes/header.php"); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_paciente = $_POST['id_paciente'];
    $fecha = $_POST['fecha_turno'];
    $hora = $_POST['hora_turno'];
    $motivo = $_POST['motivo'];
    $estado = $_POST['estado'];

    $stmt = $conn->prepare("INSERT INTO Turnos (id_pacientes, fecha_turno, hora_turno, motivo, estado) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $id_paciente, $fecha, $hora, $motivo, $estado);
    $stmt->execute();
    echo "<p>Turno registrado con éxito.</p>";
}
?>

<h3>Nuevo Turno</h3>
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

  <label>Fecha: <input type="date" name="fecha_turno" required></label><br>
  <label>Hora: <input type="time" name="hora_turno" required></label><br>
  <label>Motivo: <input type="text" name="motivo"></label><br>
  <label>Estado:
    <select name="estado">
      <option value="pendiente" selected>Pendiente</option>
      <option value="confirmado">Confirmado</option>
      <option value="cancelado">Cancelado</option>
    </select>
  </label><br>

  <button type="submit">Guardar Turno</button>
</form>

<?php include("../includes/footer.php"); ?>
