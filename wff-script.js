const wff_ajax_url = '/wp-admin/admin-ajax.php';

function toggleFormulario() {
  const form = document.getElementById('form-container');
  form.classList.toggle('show');
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

  const mensajeWhatsApp = `Hola, me gustaría más información.%0A` +
                          `Me llamo: *${nombre}*%0A` +
                          `Mi correo es: *${email}*%0A` +
                          `Mi teléfono: *${telefono}*%0A` +
                          `Servicio de interés: *${servicio}*%0A` +
                          `Mensaje: *${mensaje}*`;

  const data = new URLSearchParams({
    action: 'wff_send_email',
    nombre,
    email,
    telefono,
    servicio,
    mensaje
  });

  fetch(wff_ajax_url, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: data
  });

  const numero = "5213338087540";
  const url = `https://wa.me/${numero}?text=${mensajeWhatsApp}`;
  window.open(url, "_blank");
}
