<?php
session_start();
include("includes/db.php");

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'paciente') {
    header("Location: login.php");
    exit();
}

$mensaje = "";

$sql = "
    SELECT p.*, u.nombre_usuario
    FROM pacientes p
    INNER JOIN usuarios u ON u.id_paciente = p.id
    WHERE u.nombre_usuario = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['usuario']);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

if (!$paciente) {
    echo "No se encontraron datos del paciente.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = $_POST['nombre'];
    $apellido  = $_POST['apellido'];
    $email     = $_POST['email'];
    $telefono  = $_POST['telefono'];
    $cedula    = $_POST['cedula'];
    $fecha_nac = $_POST['fecha_nacimiento'];

    $anio_nac = date("Y", strtotime($fecha_nac));
    $anio_actual = date("Y");

    if ($anio_nac < 1930 || $anio_nac > $anio_actual) {
        $mensaje = "<div class='alert alert-danger'>⚠️ La fecha de nacimiento debe estar entre 1930 y $anio_actual.</div>";
    }
    elseif (!preg_match('/^\d{8}$/', $cedula)) {
        $mensaje = "<div class='alert alert-danger'>⚠️ La cédula uruguaya debe tener exactamente 8 números.</div>";
    } else {
        $update = "
            UPDATE pacientes 
            SET nombre = ?, apellido = ?, email = ?, telefono = ?, cedula = ?, fecha_nacimiento = ?
            WHERE id = ?
        ";
        $stmtUpdate = $conn->prepare($update);
        $stmtUpdate->bind_param(
            "ssssssi",
            $nombre,
            $apellido,
            $email,
            $telefono,
            $cedula,
            $fecha_nac,
            $paciente['id']
        );

        if ($stmtUpdate->execute()) {
            $mensaje = "<div class='alert alert-success'>✅ Perfil actualizado correctamente.</div>";
            $paciente['nombre'] = $nombre;
            $paciente['apellido'] = $apellido;
            $paciente['email'] = $email;
            $paciente['telefono'] = $telefono;
            $paciente['cedula'] = $cedula;
            $paciente['fecha_nacimiento'] = $fecha_nac;
        } else {
            $mensaje = "<div class='alert alert-danger'>❌ Error al actualizar perfil.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configuración de Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<?php include("includes/header.php"); ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="bi bi-gear me-2"></i> Configuración de Perfil
                </div>
                <div class="card-body">
                    <?= $mensaje ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required value="<?= htmlspecialchars($paciente['nombre']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" required value="<?= htmlspecialchars($paciente['apellido']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($paciente['email']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($paciente['telefono']) ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cédula (Uruguaya, 8 dígitos)</label>
                            <input type="text" name="cedula" class="form-control" maxlength="8" pattern="\d{8}" title="Debe tener 8 números" value="<?= htmlspecialchars($paciente['cedula'] ?? '') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" class="form-control"
                                   min="1930-01-01" max="<?= date("Y-m-d") ?>"
                                   value="<?= htmlspecialchars($paciente['fecha_nacimiento']) ?>" required>
                        </div>
                        
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-save me-1"></i> Guardar Cambios
                        </button>
                        <a href="panel_paciente.php" class="btn btn-secondary w-100 mt-2">
                            <i class="bi bi-arrow-left-circle me-1"></i> Volver al Panel
                        </a>
                        </form>
                        <form action="/odontologia/logout.php" method="post" class="d-inline">
                        <button type="submit" class="btn btn-danger px-4 py-2 mt-2 w-100">Cerrar sesión</button>
                        </form>
                        
                        

                </div>
            </div>
        </div>
    </div>
</div>
<?php include("includes/footer.php"); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
