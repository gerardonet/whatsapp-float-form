<?php
/*
Plugin Name: WhatsApp Float Form
Plugin URI: https://netcommerce.mx
Description: Formulario flotante con integración a WhatsApp.
Version: 1.0.2
Author: Gerardo Murillo
Author URI: https://netcommerce.mx
*/

if (!defined('ABSPATH')) exit;

// Cargar HTML + JS del formulario
require_once plugin_dir_path(__FILE__) . 'wff-html.php';

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
    $utm_source = sanitize_text_field($params['utm_source'] ?? '');
    $utm_medium = sanitize_text_field($params['utm_medium'] ?? '');
    $utm_campaign = sanitize_text_field($params['utm_campaign'] ?? '');

    $destinatario = get_option('wff_destinatario_email', get_option('admin_email'));
    $asunto = 'Nuevo mensaje del formulario flotante';
    $contenido = "Hola, recibiste un nuevo mensaje desde el formulario de tu sitio web:\n\n" .
                 "Nombre: $nombre\n" .
                 "Correo: $email\n" .
                 "Teléfono: $telefono\n" .
                 "Servicio: $servicio\n" .
                 "Mensaje: $mensaje\n\n" .
                 "UTM Source: $utm_source\n" .
                 "UTM Medium: $utm_medium\n" .
                 "UTM Campaign: $utm_campaign";

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

    add_settings_section(
        'wff_settings_section',
        'Configuración del destinatario',
        function () {
            echo '<p>Ingresa el correo donde se enviarán los mensajes desde el formulario flotante.</p>';
        },
        'wff-settings'
    );

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
