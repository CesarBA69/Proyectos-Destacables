// ========== CARRUSEL ==========
let index = 0;
const carrusel = document.getElementById("carrusel");
const total = carrusel ? carrusel.getElementsByTagName("img").length : 0;

function showNextImage() {
  if (carrusel) {
    index = (index + 1) % total;
    carrusel.style.transform = `translateX(-${index * 100}%)`;
  }
}

setInterval(showNextImage, 3000);

// ========== CARRITO ==========
const carrito = [];

function agregarAlCarrito(nombre, precio, trigger) {
  const contenedor = trigger.closest('.product-card');
  const cantidadInput = contenedor.querySelector('.cantidad');
  const cantidad = parseInt(cantidadInput.value);

  if (cantidad <= 0 || isNaN(cantidad)) return;

  const existente = carrito.find(p => p.nombre === nombre);
  if (existente) {
    existente.cantidad += cantidad;
  } else {
    carrito.push({ nombre, precio, cantidad });
  }

  actualizarContador();
  mostrarCarrito();
}

function mostrarCarrito() {
  let html = '';
  let total = 0;

  carrito.forEach(item => {
    const subtotal = item.precio * item.cantidad;
    html += `
      <div style="margin-bottom: 10px;">
        ${item.nombre} x ${item.cantidad} = $${subtotal}
        <button onclick="eliminarDelCarrito('${item.nombre}')" class="btn btn-login" style="padding: 4px 10px; font-size: 0.9rem;">❌</button>
      </div>
    `;
    total += subtotal;
  });

  document.getElementById('contenido-carrito').innerHTML = html || '<p>Tu carrito está vacío</p>';
  document.getElementById('total-pagar').innerText = 'Total: $' + total;
  document.getElementById('carrito-modal').style.display = 'block';
}

function cerrarCarrito() {
  document.getElementById('carrito-modal').style.display = 'none';
}

function actualizarContador() {
  const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
  document.getElementById('contador-carrito').innerText = totalItems;
}

function eliminarDelCarrito(nombre) {
  const index = carrito.findIndex(p => p.nombre === nombre);
  if (index !== -1) {
    carrito.splice(index, 1);
    actualizarContador();
    mostrarCarrito();
  }
}

function finalizarCompra() {
  if (carrito.length === 0) {
    alert("Tu carrito está vacío.");
    return;
  }

  let resumen = 'Gracias por tu compra:\n\n';
  let total = 0;

  carrito.forEach(item => {
    resumen += `${item.nombre} x ${item.cantidad} = $${item.precio * item.cantidad}\n`;
    total += item.precio * item.cantidad;
  });

  resumen += `\nTOTAL: $${total}`;
  alert(resumen);

  carrito.length = 0;
  actualizarContador();
  cerrarCarrito();
}

// ========== EVENTO DE CLIC EN PRODUCTO ==========
document.querySelectorAll('.product-card').forEach(card => {
  card.addEventListener('click', function (e) {
    if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT') return;

    const nombre = this.querySelector('h3').innerText;
    const precioTexto = this.querySelector('p').innerText;
    const precio = parseInt(precioTexto.replace('$', ''));
    const cantidadInput = this.querySelector('.cantidad');
    const botonAgregar = this.querySelector('button');

    agregarAlCarrito(nombre, precio, botonAgregar);
  });
});



