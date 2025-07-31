<?php
require_once 'db.php';

// Traemos los empleados con rol 'Atencion'
$sql = "SELECT id_empleado, nombre, correo FROM empleados WHERE rol = 'Atencion' ORDER BY nombre";
$resultado = $conexion->query($sql);

$empleados = [];
if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $empleados[] = $fila;
    }
}

// Obtener productos existentes
$productos = [];
$res = $conexion->query("SELECT id_producto, nombre FROM productos ORDER BY nombre");
if ($res && $res->num_rows > 0) {
  while ($p = $res->fetch_assoc()) {
    $productos[] = $p;
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .nav-right {
      display: flex;
      align-items: center;
      gap: 10px;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div class="nav-left">
      <img src="img/logo.png" class="logo" alt="Logo"/>
      <span class="nombre-logo">Admin</span>
    </div>
    <div class="nav-center">
      <button class="nav-link" onclick="location.href='index.php'">Cerrar sesión</button>
    </div>
    <div class="nav-right">
      <label for="tema-select">Tema:</label>
      <select id="tema-select" onchange="cambiarTema(this.value)">
        <option value="">-- Selecciona color --</option>
        <option value="azul">Azul</option>
        <option value="verde">Verde lima</option>
        <option value="rojo">Rojo</option>
      </select>
    </div>
  </div>

  <div class="form-container">
    <h2 class="titulo">Registrar Trabajador</h2>
    <button onclick="location.href='registro_trabajador.html'">Registrar Trabajador</button>
  </div>

  <div class="form-container">
    <h2 class="titulo">Quitar Trabajador</h2>
    <form action="eliminar_trabajador.php" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este trabajador?');">
      <select name="id_empleado" required>
        <option value="">-- Selecciona un trabajador --</option>
        <?php foreach($empleados as $empleado): ?>
          <option value="<?= $empleado['id_empleado'] ?>">
            <?= htmlspecialchars($empleado['nombre']) ?> (<?= htmlspecialchars($empleado['correo']) ?>)
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Eliminar Trabajador</button>
    </form>
  </div>

  <div class="form-container reporte">
    <h2 class="titulo">Generar Reporte</h2>
    <form action="reporte.php" method="POST" target="_blank">
      <select name="empleado" required>
        <option value="">-- Selecciona un empleado --</option>
        <?php foreach($empleados as $empleado): ?>
          <option value="<?= htmlspecialchars($empleado['nombre']) ?>">
            <?= htmlspecialchars($empleado['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <textarea name="contenido" placeholder="Escribe el contenido del reporte..." required></textarea>
      <button type="submit">Crear Reporte</button>
    </form>
  </div>

  <div class="form-container">
    <h2 class="titulo">Agregar Producto</h2>
    <form id="form-agregar-producto" enctype="multipart/form-data">
      <input type="text" name="nombre" placeholder="Nombre del producto" required>
      <input type="number" step="0.01" name="precio" placeholder="Precio" required>
      <input type="file" name="imagen" accept="image/*" required>
      <button type="submit">Agregar Producto</button>
    </form>
    <p id="mensaje-agregar"></p>
  </div>

  <div class="form-container">
    <h2 class="titulo">Eliminar Producto</h2>
    <form id="form-eliminar-producto">
      <select name="id_producto" required>
        <option value="">-- Selecciona un producto --</option>
        <?php foreach ($productos as $producto): ?>
          <option value="<?= $producto['id_producto'] ?>"><?= htmlspecialchars($producto['nombre']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit">Eliminar</button>
    </form>
    <p id="mensaje-eliminar"></p>
  </div>

  <script>
    function actualizarSelectProductos() {
      fetch('obtener_producto.php')
        .then(res => res.text())
        .then(options => {
          const select = document.querySelector('#form-eliminar-producto select[name="id_producto"]');
          select.innerHTML = '<option value="">-- Selecciona un producto --</option>' + options;
        });
    }

    document.getElementById('form-agregar-producto').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = e.target;
      const formData = new FormData(form);

      fetch('agregar_producto.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        document.getElementById('mensaje-agregar').textContent = data;
        form.reset();
        actualizarSelectProductos();
      })
      .catch(() => {
        document.getElementById('mensaje-agregar').textContent = '❌ Error en el servidor';
      });
    });

    document.getElementById('form-eliminar-producto').addEventListener('submit', function(e) {
      e.preventDefault();

      if (!confirm('¿Seguro que deseas eliminar este producto?')) return;

      const form = e.target;
      const formData = new FormData(form);

      fetch('eliminar_producto.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.text())
      .then(data => {
        document.getElementById('mensaje-eliminar').textContent = data;
        form.reset();
        actualizarSelectProductos();
      })
      .catch(() => {
        document.getElementById('mensaje-eliminar').textContent = '❌ Error en el servidor';
      });
    });

    // Cambio de tema por color
    function cambiarTema(color) {
      const body = document.body;
      const navbar = document.querySelector('.navbar');
      const botones = document.querySelectorAll('button');

      body.classList.remove('tema-azul', 'tema-verde', 'tema-rojo');
      navbar.classList.remove('tema-azul', 'tema-verde', 'tema-rojo');
      botones.forEach(b => b.classList.remove('tema-azul', 'tema-verde', 'tema-rojo'));

      if (color) {
        body.classList.add('tema-' + color);
        navbar.classList.add('tema-' + color);
        botones.forEach(b => b.classList.add('tema-' + color));
        localStorage.setItem('tema', color);
      } else {
        localStorage.removeItem('tema');
      }
    }

    // Aplicar tema guardado al cargar
    window.addEventListener('DOMContentLoaded', () => {
      const tema = localStorage.getItem('tema');
      if (tema) {
        document.body.classList.add('tema-' + tema);
        document.querySelector('.navbar').classList.add('tema-' + tema);
        document.querySelectorAll('button').forEach(b => b.classList.add('tema-' + tema));
        document.getElementById('tema-select').value = tema;
      }
    });
  </script>

</body>
</html>





