<div id="whatsapp-float" onclick="toggleFormulario()">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 800">
    <circle cx="400" cy="400" r="400" style="fill: #25d366;" />
    <g>
      <path d="M572.87,225.71c--...--Z" style="fill: #fff;" />
      <path d="M333.22,290.49h-...-9.8Z" style="fill: #fff;" />
    </g>
  </svg>
</div>

<div id="form-container">
  <h4>Por favor, compártenos tus datos para brindarte un mejor servicio.</h4>

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
