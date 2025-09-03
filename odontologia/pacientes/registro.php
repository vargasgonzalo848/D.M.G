<?php
include("../includes/db.php");

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = $_POST['email'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];

    $contrasena_hash = hash('sha256', $contrasena);

    $verificar = $conn->prepare("SELECT * FROM Usuarios WHERE nombre_usuario = ?");
    $verificar->bind_param("s", $nombre_usuario);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows > 0) {
        $mensaje = "El nombre de usuario ya existe. Elige otro.";
    } else {
        $stmt1 = $conn->prepare("INSERT INTO Pacientes (nombre, apellido, email, telefono, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
        $stmt1->bind_param("sssss", $nombre, $apellido, $email, $telefono, $fecha_nacimiento);

        if ($stmt1->execute()) {
            $id_paciente = $stmt1->insert_id;

            $stmt2 = $conn->prepare("INSERT INTO Usuarios (nombre_usuario, contrasena, rol, id_paciente) VALUES (?, ?, 'paciente', ?)");
            $stmt2->bind_param("ssi", $nombre_usuario, $contrasena_hash, $id_paciente);

            if ($stmt2->execute()) {
                session_start();
                $_SESSION['usuario'] = $nombre_usuario;
                $_SESSION['rol'] = 'paciente';
                $_SESSION['id_paciente'] = $id_paciente;
                header("Location: /odontologia/panel_paciente.php");
                exit();
            } else {
                $mensaje = "Error al registrar usuario. Intenta nuevamente.";
            }
        } else {
            $mensaje = "Error al registrar paciente. Intenta nuevamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de Paciente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">Registro de Paciente</h2>
    <form method="POST" class="card p-4 shadow mx-auto" style="max-width: 500px;">
        <div class="mb-3"><label>Nombre:</label><input type="text" name="nombre" class="form-control" required></div>
        <div class="mb-3"><label>Apellido:</label><input type="text" name="apellido" class="form-control" required></div>
        <div class="mb-3"><label>Email:</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label>Teléfono:</label><input type="text" name="telefono" class="form-control"></div>
        <div class="mb-3"><label>Fecha de nacimiento:</label><input type="date" name="fecha_nacimiento" class="form-control"></div>
        <div class="mb-3"><label>Nombre de Usuario:</label><input type="text" name="nombre_usuario" class="form-control" required></div>
        <div class="mb-3"><label>Contraseña:</label><input type="password" name="contrasena" class="form-control" required></div>
        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
        <p class="text-danger mt-2"><?= $mensaje ?></p>
    </form>
</div>
</body>
</html>
