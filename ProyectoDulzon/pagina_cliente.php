<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cerrar_sesion'])) {
    session_start();
    session_unset();
    session_destroy();
    setcookie('usuario', '', time() - 3600, "/");
    header("Location: index.php");
    exit;
}

session_start();

if (!isset($_SESSION['id_cliente']) && isset($_COOKIE['usuario'])) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=dulzon;charset=utf8', 'root', 'admin');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("SELECT id_cliente FROM clientes WHERE usuario = :usuario");
        $stmt->bindParam(':usuario', $_COOKIE['usuario'], PDO::PARAM_STR);
        $stmt->execute();

        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $_SESSION['id_cliente'] = $fila['id_cliente'];
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

if (!isset($_SESSION['id_cliente'])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Bienvenido cliente</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- BARRA DE NAVEGACIÓN -->
  <nav class="navbar">
    <div class="nav-left">
      <img src="img/logo.png" class="logo" alt="Logo">
      <span class="nombre-logo">Catálogo</span>
    </div>

    <div class="nav-center">
      <form method="post" style="display:inline;">
        <button type="submit" name="cerrar_sesion" class="nav-link">Cerrar sesión</button>
      </form>
    </div>

    <div class="nav-right">
      <label for="tema-select" style="margin-right: 5px;">🎨 Tema:</label>
      <select id="tema-select" onchange="cambiarTema(this.value)">
        <option value="">--</option>
        <option value="azul">Azul</option>
        <option value="verde">Verde lima</option>
        <option value="rojo">Rojo</option>
      </select>
      <button onclick="mostrarCarrito()" class="nav-link">🛒 Carrito (<span id="contador-carrito">0</span>)</button>
    </div>
  </nav>

  <!-- SECCIÓN DE PRODUCTOS -->
  <main style="padding: 40px;">
    <h2 style="text-align: center; margin-bottom: 40px;">Nuestros Productos</h2>

    <section style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
      <!-- Producto 1 -->
      <div class="product-card">
        <img src="img/cocacolaoreo.jpeg" alt="Producto 1">
        <h3>Coca Cola Oreo</h3>
        <p>$30</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Coca Cola Oreo', 30, this)">Agregar</button>
      </div>

      <!-- Producto 2 -->
      <div class="product-card">
        <img src="img/cocacolavainilla.jpeg" alt="Producto 2">
        <h3>Coca Cola Vainilla</h3>
        <p>$30</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Coca Cola Vainilla', 30, this)">Agregar</button>
      </div>

      <!-- Producto 3 -->
      <div class="product-card">
        <img src="img/Doritosazules.jpeg" alt="Producto 3">
        <h3>Doritos Cool Ranch</h3>
        <p>$45</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Doritos Cool Ranch', 45, this)">Agregar</button>
      </div>

      <!-- Producto 4 -->
      <div class="product-card">
        <img src="img/doritosbbq.jpeg" alt="Producto 4">
        <h3>Doritos BBQ</h3>
        <p>$45</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Doritos BBQ', 45, this)">Agregar</button>
      </div>

      <!-- Producto 5 -->
      <div class="product-card">
        <img src="img/doritosflamingnacho.jpeg" alt="Producto 5">
        <h3>Doritos Flamin Hot Nacho</h3>
        <p>$45</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Doritos Flamin Hot Nacho', 45, this)">Agregar</button>
      </div>

      <!-- Producto 6 -->
      <div class="product-card">
        <img src="img/Doritoshamburguer.jpeg" alt="Producto 6">
        <h3>Doritos Hamburguesa</h3>
        <p>$45</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Doritos Hamburguesa', 45, this)">Agregar</button>
      </div>

      <!-- Producto 7 -->
      <div class="product-card">
        <img src="img/oreomatcha.jpeg" alt="Producto 7">
        <h3>Oreo de Matcha</h3>
        <p>$75</p>
        <input type="number" min="1" value="1" class="cantidad" style="width: 60px;">
        <button class="btn btn-register" onclick="agregarAlCarrito('Oreo de Matcha', 75, this)">Agregar</button>
      </div>
    </section>
  </main>

  <!-- MODAL DEL CARRITO -->
  <div id="carrito-modal" style="display:none; position:fixed; top:10%; left:10%; right:10%; bottom:10%; background:white; border:1px solid #ccc; padding:20px; overflow:auto; z-index:999;">
    <h2>🛒 Carrito de Compras</h2>
    <div id="contenido-carrito"></div>
    <h3 id="total-pagar">Total: $0</h3>
    <button onclick="cerrarCarrito()">Cerrar</button>
    <button class="btn btn-register" onclick="finalizarCompra()">Finalizar compra</button>
  </div>

  <!-- Enlace al archivo main.js -->
  <script src="main.js"></script>

  <!-- Script para cambio de tema -->
  <script>
    function cambiarTema(color) {
      const body = document.body;
      const navbar = document.querySelector('.navbar');
      const botones = document.querySelectorAll('button');

      body.classList.remove('tema-azul', 'tema-verde', 'tema-rojo');
      navbar?.classList.remove('tema-azul', 'tema-verde', 'tema-rojo');
      botones.forEach(btn => btn.classList.remove('tema-azul', 'tema-verde', 'tema-rojo'));

      if (color) {
        body.classList.add('tema-' + color);
        navbar?.classList.add('tema-' + color);
        botones.forEach(btn => btn.classList.add('tema-' + color));
        localStorage.setItem('tema', color);
      } else {
        localStorage.removeItem('tema');
      }
    }

    window.addEventListener('DOMContentLoaded', () => {
      const tema = localStorage.getItem('tema');
      if (tema) {
        document.body.classList.add('tema-' + tema);
        const navbar = document.querySelector('.navbar');
        const botones = document.querySelectorAll('button');
        navbar?.classList.add('tema-' + tema);
        botones.forEach(btn => btn.classList.add('tema-' + tema));
        const select = document.getElementById('tema-select');
        if (select) select.value = tema;
      }
    });
  </script>

</body>
</html>
