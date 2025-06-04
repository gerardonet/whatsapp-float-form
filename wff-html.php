<!-- Estilos -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

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
  font-size: 14px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-family: 'Poppins', sans-serif;
  line-height: 20px;
}
#form-container input.error,
#form-container textarea.error,
#form-container select.error {
  border-color: red;
}
#form-container input, #form-container select {
    height: 50px;
}
.error-text {
  color: red;
  font-size: 12px;
  margin-bottom: 8px;
  font-family: 'Poppins', sans-serif;
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
  font-family: 'Poppins', sans-serif;
  font-weight: bold;
}

/* Botón de cierre */
#cerrar-formulario {
  position: absolute;
  top: 14px;
  right: 16px;
  background: transparent;
  border: none;
  padding: 5px;
  cursor: pointer;
  width: 28px;
  height: 28px;
  z-index: 10001;
}
#cerrar-formulario svg {
  width: 100%;
  height: 100%;
  fill: #555;
  transition: fill 0.2s ease;
}
#cerrar-formulario:hover svg {
  fill: #000;
}
</style>

<!-- Botón flotante con ícono -->
<div id="whatsapp-float" class="FormularioWhatsapp" onclick="toggleFormulario()">
  <svg xmlns="http://www.w3.org/2000/svg" width="61" height="61" viewBox="0 0 800 800"><circle cx="400" cy="400" r="400" style="fill:#25d366"/><path d="M572.87 225.71c-46.15-45.98-107.54-71.31-172.88-71.34-65.19 0-126.68 25.31-173.14 71.28-46.54 46.04-72.19 107.24-72.25 172.08v.12c0 39.25 10.32 78.84 29.89 114.98l-29.22 132.81 134.34-30.56c34.02 17.15 71.99 26.19 110.28 26.2h.1c65.18 0 126.67-25.32 173.13-71.29 46.58-46.08 72.24-107.19 72.27-172.08.02-64.43-25.73-125.58-72.52-172.2ZM399.99 602.93h-.09c-34.38-.01-68.45-8.65-98.51-24.97l-6.35-3.45-89.33 20.32 19.4-88.18-3.74-6.45c-18.6-32.07-28.43-67.48-28.43-102.4.07-113.03 92.94-205.09 207.04-205.09 55.12.02 106.91 21.39 145.83 60.16 39.51 39.37 61.26 90.88 61.24 145.03-.04 113.06-92.93 205.03-207.06 205.03Z" style="fill:#fff"/><path d="M333.22 290.49h-10.76c-3.74 0-9.82 1.4-14.97 7s-19.65 19.13-19.65 46.66 20.12 54.12 22.92 57.86c2.81 3.73 38.83 62.02 95.88 84.45 47.42 18.64 57.07 14.93 67.36 14 10.29-.93 33.21-13.53 37.89-26.59s4.68-24.26 3.28-26.6c-1.41-2.33-5.15-3.73-10.76-6.53-5.61-2.8-33.13-16.56-38.27-18.43-5.15-1.86-8.89-2.8-12.63 2.81-3.74 5.59-14.77 18.53-18.04 22.26-3.27 3.74-6.55 4.21-12.16 1.41-5.61-2.81-23.51-8.82-44.95-27.87-16.69-14.83-28.27-33.74-31.55-39.34-3.27-5.6-.35-8.62 2.47-11.42 2.52-2.5 5.93-5.93 8.74-9.19 2.8-3.27 3.6-5.6 5.48-9.33 1.87-3.73.93-7-.47-9.8-1.41-2.8-12.18-30.46-17.17-41.53-4.21-9.32-8.63-9.63-12.63-9.8Z" style="fill:#fff"/></svg>
</div>

<!-- Formulario -->
<div id="form-container">
  <!-- Botón de cierre -->
  <button id="cerrar-formulario" onclick="toggleFormulario()" aria-label="Cerrar formulario">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
      <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z"/>
    </svg>
  </button>

  <h4 style="margin-top:0; color:#000; font-size:15px; font-weight:400;text-transform: none;max-width: 85%;font-family: 'Poppins', sans-serif;">
    Compártenos tus datos para brindarte un mejor servicio.
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

  <button id="submit-button" class="AbrirWhatsapp" onclick="enviarAWhatsApp()" disabled>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" style="width:20px; height:20px; vertical-align:middle; margin-right:3px;margin-bottom:2px; fill:currentColor;">
      <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7 .9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
    </svg>
    Abrir WhatsApp
  </button>

</div>

<!-- Scripts -->
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

  const numero = window.WFF.numeroWhatsapp || "5213338087540";
  const url = `https://wa.me/${numero}?text=${mensajeWhatsApp}`;
  window.open(url, "_blank");

  // Enviar correo después, con manejo de errores
  fetch('/wp-json/wff/v1/enviar-correo/', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      nombre,
      email,
      telefono,
      servicio,
      mensaje,
      utm_source,
      utm_medium,
      utm_campaign
    })
  })
  .then(res => {
    if (!res.ok) {
      console.error('Error en el envío del correo:', res.statusText);
    }
  })
  .catch(err => {
    console.error('Fallo de red al intentar enviar el correo:', err);
  });
}
</script>
