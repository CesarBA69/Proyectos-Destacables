<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
  session_unset();
  session_destroy();
  setcookie('usuario', '', time() - 3600, "/");
  header("Location: index.php");
  exit;
}

if (!isset($_SESSION['id_empleado']) && isset($_COOKIE['usuario'])) {
  $_SESSION['usuario'] = $_COOKIE['usuario'];

  try {
    $pdo = new PDO('mysql:host=localhost;dbname=dulzon;charset=utf8', 'root', 'admin');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id_empleado, rol FROM empleados WHERE usuario = :usuario");
    $stmt->bindParam(':usuario', $_COOKIE['usuario'], PDO::PARAM_STR);
    $stmt->execute();

    $fila = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($fila && $fila['rol'] === 'Atencion') {
      $_SESSION['id_empleado'] = $fila['id_empleado'];
    } else {
      session_unset();
      session_destroy();
      setcookie('usuario', '', time() - 3600, "/");
      header("Location: login.html");
      exit;
    }
  } catch (PDOException $e) {
    header("Location: login.html");
    exit;
  }
}

if (!isset($_SESSION['id_empleado'])) {
  header("Location: login.html");
  exit;
}

try {
  $pdo = new PDO('mysql:host=localhost;dbname=dulzon;charset=utf8', 'root', 'admin');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $pdo->query("SELECT id_empleado, nombre FROM empleados ORDER BY nombre ASC");
  $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  $empleados = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Atención - Reporte de Ventas</title>
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

  <div class="navbar">
    <div class="nav-left">
      <img src="img/logo.png" alt="Logo" class="logo" />
      <span class="nombre-logo">Trabajador</span>
    </div>
    <div class="nav-center">
      <form method="post" style="display:inline;">
        <button type="submit" name="cerrar_sesion" class="nav-link">Cerrar sesión</button>
      </form>
    </div>
  </div>

  <div class="form-container">
    <h2>Generar Reporte de Venta</h2>

    <select id="empleado" name="empleado" required>
      <option value="">-- Selecciona un empleado --</option>
      <?php foreach ($empleados as $empleado): ?>
        <option value="<?= htmlspecialchars($empleado['id_empleado']) ?>">
          <?= htmlspecialchars($empleado['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <form id="venta-form" onsubmit="return false;">
      <select id="producto">
        <option value="30">Coca-Cola Oreo - $30</option>
        <option value="30">Coca-Cola Vainilla - $30</option>
        <option value="45">Doritos Azules - $45</option>
        <option value="45">Doritos BBQ - $45</option>
        <option value="45">Doritos Flaming Nacho - $45</option>
        <option value="45">Doritos Hamburguesa - $45</option>
        <option value="75">Oreo Matcha - $75</option>
      </select>
      <input type="number" id="cantidad" placeholder="Cantidad" min="1" required>
      <button type="button" onclick="agregarProducto()">Agregar</button>
    </form>

    <div id="reporte-pdf">
      <h3>Empleado: <span id="nombre-empleado">-</span></h3>
      <div id="lista-venta"></div>
      <h3>Total: $<span id="total">0</span></h3>
    </div>

    <button id="descargar-pdf">Descargar PDF</button>

    <form id="form-reporte" action="reporteventas.php" method="POST" target="_blank" style="display: none;">
      <input type="hidden" name="nombre_empleado" id="input-empleado">
      <input type="hidden" name="ventas" id="input-ventas">
    </form>
  </div>

  <script>
    let totalVenta = 0;
    const ventas = [];

    function agregarProducto() {
      const selectEmpleado = document.getElementById('empleado');
      const selectProducto = document.getElementById('producto');
      const cantidadInput = document.getElementById('cantidad');

      const cantidad = parseInt(cantidadInput.value);
      if (!cantidad || cantidad <= 0 || selectEmpleado.value === "") {
        return alert("Por favor selecciona un empleado y una cantidad válida.");
      }

      const precio = parseInt(selectProducto.value);
      const nombreProducto = selectProducto.options[selectProducto.selectedIndex].text.split(" - ")[0];
      const subtotal = precio * cantidad;

      const lista = document.getElementById('lista-venta');
      const item = document.createElement('p');
      item.textContent = `${nombreProducto} x${cantidad} = $${subtotal}`;
      lista.appendChild(item);

      ventas.push({
        producto: nombreProducto,
        cantidad: cantidad,
        subtotal: subtotal
      });

      totalVenta += subtotal;
      document.getElementById('total').textContent = totalVenta;

      const nombreEmpleado = selectEmpleado.options[selectEmpleado.selectedIndex].text;
      document.getElementById('nombre-empleado').textContent = nombreEmpleado;

      cantidadInput.value = "";
    }

    document.getElementById('descargar-pdf').addEventListener('click', () => {
      if (ventas.length === 0) {
        alert("No has agregado productos.");
        return;
      }

      const nombreEmpleado = document.getElementById('nombre-empleado').textContent;
      if (nombreEmpleado === '-') {
        alert("Selecciona un empleado.");
        return;
      }

      document.getElementById('input-empleado').value = nombreEmpleado;
      document.getElementById('input-ventas').value = JSON.stringify(ventas);

      document.getElementById('form-reporte').submit();
    });
  </script>
</body>
</html>

