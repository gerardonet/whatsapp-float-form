<?php
$numero_base = get_option('wff_whatsapp_number', ''); // 10 dígitos
$numero_completo = '521' . preg_replace('/\D/', '', $numero_base);
?>

<!-- Estilos -->
<style>
/* ... (todos los estilos permanecen igual que antes) ... */
</style>

<!-- Botón flotante con ícono -->
<div id="whatsapp-float" onclick="toggleFormulario()">
  <!-- SVG del ícono -->
</div>

<!-- Contenedor de Formulario -->
<div id="form-container">
  <h4 style="margin-top:0; color:#000; font-size:15px; font-weight:400;">
    Por favor, compártenos tus datos para brindarte un mejor servicio.
  </h4>

  <input type="text" id="nombre" placeholder="Tu nombre" oninput="verificarCampos()">
  <input type="email" id="email" placeholder="Tu email" oninput="verificarCampos()" onblur="validarEmail()">
  <div id="email-error" class="error-text">Por favor ingresa un correo válido.</div>

  <input type="tel" id="telefono" placeholder="Tu teléfono" oninput="verificarCampos()" onblur="validarTelefono()">
  <div id="telefono-error" class="error-text">El teléfono debe tener exactamente 10 dígitos.</div>

  <select id="servicio" onchange="verificarCampos()" onblur="validarServicio()">
    <option value="">- ¿Qué servicio te interesa? -</option>
    <option>Web Design</option>
    <option>Web Development</option>
    <option>Web Marketing</option>
    <option>Web Hosting</option>
    <option>E-Commerce</option>
    <option>Branding</option>
  </select>
  <div id="servicio-error" class="error-text">Por favor selecciona un servicio.</div>

  <textarea id="mensaje" rows="3" placeholder="Cuéntanos en qué podemos ayudarte" oninput="verificarCampos()"></textarea>

  <button id="submit-button" onclick="enviarAWhatsApp()" disabled>Abrir WhatsApp</button>
</div>

<script>
function toggleFormulario() {
  document.getElementById('form-container').classList.toggle('show');
}

function verificarCampos() {
  const nombre = document.getElementById('nombre').value.trim();
  const email = document.getElementById('email').value.trim();
  const telefono = document.getElementById('telefono').value.trim();
  const servicio = document.getElementById('servicio').value.trim();
  const mensaje = document.getElementById('mensaje').value.trim();
  const boton = document.getElementById('submit-button');

  const emailValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  const telefonoValido = /^[0-9]{10}$/.test(telefono);
  const servicioValido = servicio !== '';

  const camposLlenos = nombre && email && telefono && servicio && mensaje;
  const todosValidos = camposLlenos && emailValido && telefonoValido && servicioValido;

  boton.disabled = !todosValidos;
}

function validarEmail() {
  const email = document.getElementById('email');
  const error = document.getElementById('email-error');
  const valido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim());

  error.style.display = !valido && email.value.trim() ? 'block' : 'none';
  email.classList.toggle('error', !valido && email.value.trim());
}

function validarTelefono() {
  const tel = document.getElementById('telefono');
  const error = document.getElementById('telefono-error');
  const valido = /^[0-9]{10}$/.test(tel.value.trim());

  error.style.display = !valido && tel.value.trim() ? 'block' : 'none';
  tel.classList.toggle('error', !valido && tel.value.trim());
}

function validarServicio() {
  const select = document.getElementById('servicio');
  const error = document.getElementById('servicio-error');
  const valido = select.value.trim() !== '';

  error.style.display = !valido ? 'block' : 'none';
  select.classList.toggle('error', !valido);
}

function enviarAWhatsApp() {
  const nombre = document.getElementById('nombre').value.trim();
  const email = document.getElementById('email').value.trim();
  const telefono = document.getElementById('telefono').value.trim();
  const servicio = document.getElementById('servicio').value;
  const mensaje = document.getElementById('mensaje').value.trim();

  const utm_source = new URLSearchParams(window.location.search).get('utm_source') || '';
  const utm_medium = new URLSearchParams(window.location.search).get('utm_medium') || '';
  const utm_campaign = new URLSearchParams(window.location.search).get('utm_campaign') || '';

  const mensajeWhatsApp = `Hola, me gustaría más información.%0A` +
                          `Me llamo: *${nombre}*%0A` +
                          `Mi correo es: *${email}*%0A` +
                          `Mi teléfono: *${telefono}*%0A` +
                          `Servicio de interés: *${servicio}*%0A` +
                          `Mensaje: *${mensaje}*`;

  fetch('/wp-json/wff/v1/enviar-correo/', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ nombre, email, telefono, servicio, mensaje, utm_source, utm_medium, utm_campaign })
  });

  const numero = "<?php echo esc_js($numero_completo); ?>";
  const url = `https://wa.me/${numero}?text=${mensajeWhatsApp}`;
  window.open(url, "_blank");
}
</script>
