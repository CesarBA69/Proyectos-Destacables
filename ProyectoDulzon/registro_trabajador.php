<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 
    $nombre = $conexion->real_escape_string(trim($_POST['nombre'] ?? ''));
    $usuario = $conexion->real_escape_string(trim($_POST['usuario'] ?? ''));
    $correo = $conexion->real_escape_string(trim($_POST['correo'] ?? ''));

    // se captura la contraseña original (sin cifrar)
    $contrasena_original = $_POST['contrasena'] ?? '';

    // aqui aplicamos md5 para cifrar la contraseña antes de guardarla en la BD
    $contrasena = md5($contrasena_original);

    $rol = $conexion->real_escape_string(trim($_POST['rol'] ?? ''));

    if ($nombre === '' || $usuario === '' || $correo === '' || $contrasena_original === '' || $rol === '') {
        $error = "Por favor completa todos los campos obligatorios.";
    }
    
    elseif (!in_array($rol, ['Admin', 'Atencion'])) {
        $error = "Rol no válido.";
    }
    
    elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico no válido.";
    }
    
    else {
        // valida que no se dupliquen los usuarios o correos 
        $stmt = $conexion->prepare("SELECT id_empleado FROM empleados WHERE usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $usuario, $correo);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            $error = "El usuario o correo ya están registrados.";
            $stmt->close();
        } else {
            $stmt->close();

            // contraseña cifrada en md5
            $stmt = $conexion->prepare("INSERT INTO empleados (nombre, correo, usuario, contrasena, rol) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $nombre, $correo, $usuario, $contrasena, $rol);

            if ($stmt->execute()) {
                $exito = true;
            } else {
                $error = "Error al registrar trabajador: " . $conexion->error;
            }
            $stmt->close();
        }
    }
    $conexion->close();
} else {
    $error = "No se enviaron datos.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro Empleado</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const tema = localStorage.getItem('tema');
    if (tema) {
      document.body.classList.add('tema-' + tema);
      const navbar = document.querySelector('.navbar');
      const botones = document.querySelectorAll('button');
      if (navbar) navbar.classList.add('tema-' + tema);
      botones.forEach(btn => btn.classList.add('tema-' + tema));
    }
  });
</script>

</head>
<body>
<script>
    <?php if (isset($exito) && $exito): ?>
        Swal.fire({
            icon: 'success',
            title: 'Registro exitoso',
            confirmButtonText: 'Aceptar',
            timer: 10000,
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
        }).then(() => {
            window.location.href = 'login.html';
        });
    <?php elseif (isset($error)): ?>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: <?= json_encode($error) ?>,
            confirmButtonText: 'Aceptar'
        }).then(() => {
            window.history.back();
        });
    <?php endif; ?>
</script>
</body>
</html>
