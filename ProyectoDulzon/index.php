<?php
require_once 'db.php';

$productos = [];
$resultado = $conexion->query("SELECT nombre FROM productos ORDER BY id_producto DESC");

if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $productos[] = $row['nombre'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dulzón</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css" />

  <style>
    .carrusel-viewport {
      width: 100%;
      max-width: 600px;
      overflow: hidden;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .carrusel-imagenes {
      display: flex;
      transition: transform 0.5s ease;
    }

    .carrusel-imagenes img {
      min-width: 100%;
      height: 350px;
      object-fit: cover;
    }

    .hidden {
      display: none;
    }

    .boton-ayuda {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      font-size: 24px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      cursor: pointer;
      z-index: 999;
    }

    .boton-ayuda:hover {
      background-color: #218838;
    }
  </style>
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
  <header class="navbar">
    <div class="nav-left">
      <img src="img/logo.png" alt="Logo" class="logo" />
      <span class="nombre-logo">Dulzón</span>
    </div>

    <nav class="nav-center">
      <button class="nav-link" onclick="mostrarSeccion('inicio')">Inicio</button>
      <button class="nav-link" onclick="mostrarSeccion('promociones')">Promociones</button>
      <button class="nav-link" onclick="mostrarSeccion('productos')">Productos</button>
    </nav>

    <div class="nav-right">
      <button class="btn btn-login" onclick="location.href='login.html'">Iniciar sesión</button>
      <button class="btn btn-register" onclick="location.href='registro_cliente.html'">Regístrate</button>
    </div>
  </header>

  <main id="contenido">
    <!-- CARRUSEL DINÁMICO -->
    <section id="inicio" class="seccion">
      <h2 style="text-align: center;">Novedades</h2>
      <div class="carrusel-viewport">
        <div id="carrusel" class="carrusel-imagenes">
          <?php foreach ($productos as $nombre): ?>
            <img src="img/<?= strtolower(str_replace(' ', '', $nombre)) ?>.jpeg" alt="<?= htmlspecialchars($nombre) ?>">
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- PROMOCIONES ESTÁTICAS -->
    <section id="promociones" class="seccion hidden" align="center">
      <h2>Promociones</h2>
      <table style="margin:auto;">
        <tr>
          <td><img src="img/doritosazules.jpeg" width="150" height="150"><br><strong>Doritos Azules 2x1</strong></td>
          <td><img src="img/doritosbbq.jpeg" width="150" height="150"><br><strong>Doritos BBQ 2x1</strong></td>
          <td><img src="img/doritosflamingnacho.jpeg" width="150" height="150"><br><strong>Doritos Flaming Nacho 2x1</strong></td>
        </tr>
      </table>
    </section>

    <!-- PRODUCTOS DINÁMICOS -->
    <section id="productos" class="seccion hidden" align="center">
      <h2>Productos</h2>
      <table style="margin:auto;">
        <?php foreach (array_chunk($productos, 3) as $fila): ?>
          <tr>
            <?php foreach ($fila as $producto): ?>
              <td>
                <img src="img/<?= strtolower(str_replace(' ', '', $producto)) ?>.jpeg" width="150" height="150" alt="<?= htmlspecialchars($producto) ?>"><br>
                <strong><?= htmlspecialchars($producto) ?></strong>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </table>
    </section>
  </main>

  <!-- Botón de Ayuda -->
  <button class="boton-ayuda" onclick="mostrarAyuda()" title="Ayuda">?</button>

  <script>
    function mostrarSeccion(id) {
      const secciones = document.querySelectorAll('.seccion');
      secciones.forEach(sec => sec.classList.add('hidden'));
      document.getElementById(id).classList.remove('hidden');
    }

    // Carrusel automático
    let index = 0;
    const carrusel = document.getElementById("carrusel");
    const total = carrusel.getElementsByTagName("img").length;

    function showNextImage() {
      index = (index + 1) % total;
      carrusel.style.transform = `translateX(-${index * 100}%)`;
    }

    setInterval(showNextImage, 3000);

    // Función de ayuda
    function mostrarAyuda() {
      alert(        "🟢 Bienvenido a Dulzón - Sistema de Gestión para Dulcerías\n\n" +
        " ¿Cómo funciona?\n\n" +
        "1. Navegación:\n" +
        "- En la parte superior tienes tres secciones principales: Inicio, Promociones y Productos.\n" +
        "- Usa los botones para cambiar entre estas secciones sin recargar la página.\n\n" +
        "2. Promociones:\n" +
        "- Se muestran ofertas especiales y descuentos actuales.\n" +
        "- Puedes ver imágenes y descripciones de las promociones vigentes.\n\n" +
        "3. Productos:\n" +
        "- Catálogo completo de productos disponibles en la dulcería.\n" +
        "- Cada producto incluye imagen y nombre.\n\n" +
        "4. Cuenta de Usuario:\n" +
        "- En la esquina superior derecha puedes iniciar sesión si ya tienes cuenta.\n" +
        "- Si eres nuevo, regístrate para poder realizar compras y acceder a funciones personalizadas.\n\n" +
        "¡Gracias por usar Dulzón! Si tienes dudas, contacta con el administrador.- badillomsilva264@gmail.com, cesaron998@gmail.com");
    }
  </script>
</body>

</html>


