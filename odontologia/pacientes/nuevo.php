<?php include("../includes/db.php"); ?>
<?php include("../includes/header.php"); ?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha = $_POST['fecha_nacimiento'];

    $stmt = $conn->prepare("INSERT INTO Pacientes (nombre, apellido, email, telefono, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $apellido, $email, $telefono, $fecha);
    $stmt->execute();
    echo "<p>Paciente registrado con éxito.</p>";
}
?>

<form method="post">
  <label>Nombre: <input type="text" name="nombre" required></label><br>
  <label>Apellido: <input type="text" name="apellido" required></label><br>
  <label>Email: <input type="email" name="email" required></label><br>
  <label>Teléfono: <input type="text" name="telefono"></label><br>
  <label>Fecha de nacimiento: <input type="date" name="fecha_nacimiento"></label><br>
  <button type="submit">Guardar</button>
</form>

<?php include("../includes/footer.php"); ?>
