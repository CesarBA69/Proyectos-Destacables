<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $conexion->real_escape_string($_POST['nombre']);
    $usuario = $conexion->real_escape_string($_POST['usuario']);
    $correo = $conexion->real_escape_string($_POST['correo']);
    $contrasena = $_POST['contrasena'];

    if (!preg_match('/^[a-z]+$/', $contrasena)) {
        $error = "La contraseña debe contener solo letras minúsculas.";
    } else {
        $contrasena_hash = md5($contrasena); 

        $sql = "INSERT INTO clientes (nombre, usuario, correo, contrasena) 
                VALUES ('$nombre', '$usuario', '$correo', '$contrasena_hash')";
        if ($conexion->query($sql) === TRUE) {
            $exito = true;
        } else {
            $error = "Error al registrar: " . $conexion->error;
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
    <title>Registro Cliente</title>
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
                timer: 10000, // 10 segundos
                timerProgressBar: true,
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
            }).then(() => {
                window.location.href = 'login.php';
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
