<!-- Estilos -->
<style>
#whatsapp-float {
  position: fixed;
  bottom: 40px;
  right: 20px;
  background-color: transparent;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  transition: transform 0.25s ease, box-shadow 0.25s ease;
}
#whatsapp-float:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.3);
}
#whatsapp-float:hover svg circle {
  fill: #128C7E !important;
}
#form-container {
  position: fixed;
  bottom: 110px;
  right: 20px;
  background: white;
  padding: 20px;
  border-radius: 10px;
  max-width: 320px;
  width: 90%;
  font-family: sans-serif;
  box-shadow: 0 5px 20px rgba(0,0,0,0.2);
  z-index: 10000;
  opacity: 0;
  transform: translateY(20px);
  transition: all 0.4s ease;
  pointer-events: none;
  visibility: hidden;
}
#form-container.show {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
  visibility: visible;
}
#form-container input,
#form-container select,
#form-container textarea {
  color: #000;
  width: 100%;
  margin-bottom: 5px;
  padding: 8px;
  font-family: sans-serif;
  font-size: 14px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
}
#form-container input.error,
#form-container textarea.error,
#form-container select.error {
  border-color: red;
}
.error-text {
  color: red;
  font-size: 12px;
  margin-bottom: 8px;
  font-family: sans-serif;
  display: none;
}
#form-container textarea {
  resize: vertical;
}
#submit-button:disabled {
  background-color: #ccc !important;
  cursor: not-allowed;
  color: #666;
}
#submit-button {
  background-color: #25D366;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 5px;
  width: 100%;
  font-size: 14px;
  font-family: sans-serif;
}
</style>

<!-- Botón flotante -->
<div id="whatsapp-float" onclick="toggleFormulario()">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800">
    <circle cx="400" cy="400" r="400" style="fill: #25d366;" />
    <g>
      <!-- Ícono de WhatsApp -->
      <path d="M572.87,225.71c...Z" style="fill: #fff;" />
      <path d="M333.22,290.49h-10.76c...Z" style="fill: #fff;" />
    </g>
  </svg>
</div>

<!-- Contenedor del formulario -->
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

<!-- Número dinámico de WhatsApp -->
<script>
  const numero = "<?php echo esc_js(get_option('wff_whatsapp_number', '5213338087540')); ?>";
</script>

<!-- Scripts funcionales -->
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
  boton.disabled = !(camposLlenos && emailValido && telefonoValido && servicioValido);
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

  const urlParams = new URLSearchParams(window.location.search);
  const utm_source = urlParams.get('utm_source') || '';
  const utm_medium = urlParams.get('utm_medium') || '';
  const utm_campaign = urlParams.get('utm_campaign') || '';

  const mensajeWhatsApp = `Hola, me gustaría más información.%0A` +
    `Me llamo: *${nombre}*%0A` +
    `Mi correo es: *${email}*%0A` +
    `Mi teléfono: *${telefono}*%0A` +
    `Servicio de interés: *${servicio}*%0A` +
    `Mensaje: *${mensaje}*`;

  fetch('/wp-json/wff/v1/enviar-correo/', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      nombre, email, telefono, servicio, mensaje,
      utm_source, utm_medium, utm_campaign
    })
  });

  window.open(`https://wa.me/${numero}?text=${mensajeWhatsApp}`, "_blank");
}
</script>
