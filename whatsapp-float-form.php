<?php
/*
Plugin Name: WhatsApp Float Form
Plugin URI: https://netcommerce.mx
Description: Botón flotante con integración a WhatsApp. Recibe avisos por correo de cada lead que se ponga en contacto.
Version: 1.2.0
Requires at least: 6.0
Tested up to: 6.8.1
Requires PHP: 8.0
Author: Gerardo Murillo
Author URI: https://netcommerce.mx
*/

if (!defined('ABSPATH')) exit;

// Cargar HTML + JS del formulario
add_action('wp_footer', function () {
    include plugin_dir_path(__FILE__) . 'wff-html.php';
});

// Cargar clase de actualización desde GitHub
require_once plugin_dir_path(__FILE__) . 'updater.php';
new WP_GitHub_Updater(__FILE__);

// Endpoint para recibir datos y enviar correo
add_action('rest_api_init', function () {
    register_rest_route('wff/v1', '/enviar-correo/', [
        'methods'  => 'POST',
        'callback' => 'wff_enviar_correo',
        'permission_callback' => '__return_true',
    ]);
});

function wff_enviar_correo($request) {
    $params = $request->get_json_params();

    $nombre = sanitize_text_field($params['nombre'] ?? '');
    $email = sanitize_email($params['email'] ?? '');
    $telefono = sanitize_text_field($params['telefono'] ?? '');
    $servicio = sanitize_text_field($params['servicio'] ?? '');
    $mensaje = sanitize_textarea_field($params['mensaje'] ?? '');
    $utm_campaign = sanitize_text_field($params['utm_campaign'] ?? '');

    $dominio = parse_url(home_url(), PHP_URL_HOST);
    $destinatario = get_option('wff_destinatario_email', get_option('admin_email'));
    $asunto = "Nuevo lead desde $dominio";

    $contenido = "Hola, recibiste un nuevo lead desde $dominio:\n\n" .
                 "Nombre: $nombre\n" .
                 "Correo: $email\n" .
                 "Teléfono: $telefono\n" .
                 (!empty($servicio) ? "Servicio: $servicio\n" : '') .
                 "Mensaje: $mensaje\n\n" .
                 "Campaña: $utm_campaign";

    wp_mail($destinatario, $asunto, $contenido);

    return rest_ensure_response(['success' => true]);
}


// Configuración desde el panel de WordPress
add_action('admin_menu', function () {
    add_options_page(
        'WhatsApp Float Form',
        'WhatsApp Float Form',
        'manage_options',
        'wff-settings',
        'wff_render_settings_page'
    );
});

add_action('admin_init', function () {
    register_setting('wff_settings_group', 'wff_destinatario_email');
    register_setting('wff_settings_group', 'wff_whatsapp_number');

    // Mostrar/ocultar el campo de servicio
    register_setting('wff_settings_group', 'wff_mostrar_servicio');
    add_settings_field(
        'wff_mostrar_servicio',
        '¿Mostrar campo "Servicio"?',
        function () {
            $checked = checked(1, get_option('wff_mostrar_servicio', 1), false);
            echo "<input type='checkbox' name='wff_mostrar_servicio' value='1' $checked />";
        },
        'wff-settings',
        'wff_settings_section'
    );

// Lista de opciones del campo servicio
    register_setting('wff_settings_group', 'wff_opciones_servicio');
    add_settings_field(
        'wff_opciones_servicio',
        'Opciones del campo "Servicio" (una por línea)',
        function () {
            $value = esc_textarea(get_option('wff_opciones_servicio', "Web Design\nWeb Development\nMarketing"));
            echo "<textarea name='wff_opciones_servicio' rows='5' style='width: 300px;'>$value</textarea>";
        },
        'wff-settings',
        'wff_settings_section'
    );


    add_settings_section(
        'wff_settings_section',
        'Configuración del destinatario',
        function () {
            echo '<p>Ingresa los datos donde se enviarán los mensajes desde el formulario flotante.</p>';
        },
        'wff-settings'
    );

    // Campo de email
    add_settings_field(
        'wff_destinatario_email',
        'Correo destinatario',
        function () {
            $value = get_option('wff_destinatario_email', get_option('admin_email'));
            echo "<input type='email' name='wff_destinatario_email' value='" . esc_attr($value) . "' style='width: 300px;' />";
        },
        'wff-settings',
        'wff_settings_section'
    );

    // Campo de número de WhatsApp
add_settings_field(
    'wff_whatsapp_number',
    'Número de WhatsApp (10 dígitos sin signos)',
    function () {
        $value = get_option('wff_whatsapp_number', '');
        echo "<input type='text' name='wff_whatsapp_number' value='" . esc_attr($value) . "' pattern='[0-9]{10}' maxlength='10' style='width: 300px;' required />";
    },
    'wff-settings',
    'wff_settings_section'
);

});

function wff_render_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de WhatsApp Float Form</h1>
        <form method="post" action="options.php">
            <?php
                settings_fields('wff_settings_group');
                do_settings_sections('wff-settings');
                submit_button();
            ?>
        </form>
    </div>
    <?php
}

add_action('wp_footer', function () {
    $numero_base = get_option('wff_whatsapp_number', '');
    $numero = '521' . preg_replace('/\D/', '', $numero_base);

    $mostrar_servicio = get_option('wff_mostrar_servicio', 1) ? 'true' : 'false';
    $opciones_raw = get_option('wff_opciones_servicio', "Web Design\nWeb Development\nMarketing");
    $opciones_array = array_map('trim', explode("\n", $opciones_raw));
    $opciones_json = json_encode($opciones_array);

    echo "<script>
        window.WFF = window.WFF || {};
        window.WFF.numeroWhatsapp = '{$numero}';
        window.WFF.mostrarServicio = {$mostrar_servicio};
        window.WFF.opcionesServicio = {$opciones_json};
    </script>";
});

