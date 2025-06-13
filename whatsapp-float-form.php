<?php
/*
Plugin Name: WhatsApp Float Form
Plugin URI: https://netcommerce.mx
Description: Botón flotante con integración a WhatsApp. Recibe avisos por correo de cada lead que se ponga en contacto.
Version: 1.2.2
Requires at least: 6.0
Tested up to: 6.8.1
Requires PHP: 8.0
Author: Gerardo Murillo
Author URI: https://netcommerce.mx
*/

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
    // Validaciones personalizadas
    register_setting('wff_settings_group', 'wff_destinatario_email', [
        'sanitize_callback' => function ($input) {
            $email = sanitize_email($input);
            if (!is_email($email)) {
                add_settings_error('wff_destinatario_email', 'invalid_email', 'El correo electrónico no es válido.', 'error');
                return get_option('wff_destinatario_email');
            }
            return $email;
        }
    ]);

    register_setting('wff_settings_group', 'wff_whatsapp_number', [
        'sanitize_callback' => function ($input) {
            $input = preg_replace('/\D/', '', $input);
            if (strlen($input) !== 10) {
                add_settings_error('wff_whatsapp_number', 'invalid_number', 'El número de WhatsApp debe tener exactamente 10 dígitos.', 'error');
                return get_option('wff_whatsapp_number');
            }
            return $input;
        }
    ]);

    register_setting('wff_settings_group', 'wff_mostrar_servicio');
    register_setting('wff_settings_group', 'wff_opciones_servicio');
    register_setting('wff_settings_group', 'wff_etiqueta_servicio');

    add_settings_section('wff_settings_section', 'Configuración del formulario flotante', function () {
        echo '<p>Personaliza los datos de contacto y comportamiento del formulario flotante de WhatsApp.</p>';
    }, 'wff-settings');

    add_settings_field('wff_destinatario_email', 'Correo destinatario', function () {
        $value = get_option('wff_destinatario_email', get_option('admin_email'));
        echo "<input type='email' name='wff_destinatario_email' value='" . esc_attr($value) . "' style='width: 300px;' required />";
    }, 'wff-settings', 'wff_settings_section');

    add_settings_field('wff_whatsapp_number', 'Número de WhatsApp (10 dígitos sin signos)', function () {
        $value = get_option('wff_whatsapp_number', '');
        echo "<input type='text' name='wff_whatsapp_number' value='" . esc_attr($value) . "' pattern='[0-9]{10}' maxlength='10' style='width: 300px;' required />";
    }, 'wff-settings', 'wff_settings_section');

    add_settings_field('wff_mostrar_servicio', '¿Mostrar campo "Servicio"?', function () {
        $checked = checked(1, get_option('wff_mostrar_servicio', 1), false);
        echo "<input type='checkbox' name='wff_mostrar_servicio' value='1' $checked />";
    }, 'wff-settings', 'wff_settings_section');

    add_settings_field('wff_etiqueta_servicio', 'Etiqueta del campo servicio', function () {
        $value = esc_attr(get_option('wff_etiqueta_servicio', '¿Qué servicio te interesa?'));
        echo "<input type='text' name='wff_etiqueta_servicio' value='$value' style='width: 300px;' />";
    }, 'wff-settings', 'wff_settings_section');

    add_settings_field('wff_opciones_servicio', 'Opciones del campo "Servicio" (una por línea)', function () {
        $value = esc_textarea(get_option('wff_opciones_servicio', "Web Design\nWeb Development\nMarketing"));
        echo "<textarea name='wff_opciones_servicio' rows='5' style='width: 300px;'>$value</textarea>";
    }, 'wff-settings', 'wff_settings_section');
});

function wff_render_settings_page() {
    echo '<div class="wrap">';
    echo '<h1>Configuración de WhatsApp Float Form</h1>';
    echo '<form method="post" action="options.php">';
    settings_fields('wff_settings_group');
    do_settings_sections('wff-settings');
    submit_button();
    echo '</form>';
    echo '</div>';
}

add_action('wp_footer', function () {
    $numero_base = get_option('wff_whatsapp_number', '');
    $numero = '521' . preg_replace('/\D/', '', $numero_base);
    $correo = get_option('wff_destinatario_email', get_option('admin_email'));
    $mostrar_servicio = get_option('wff_mostrar_servicio', 1) ? 'true' : 'false';
    $etiqueta_servicio = esc_attr(get_option('wff_etiqueta_servicio', '¿Qué servicio te interesa?'));
    $opciones_raw = get_option('wff_opciones_servicio', "Web Design\nWeb Development\nMarketing");
    $opciones_array = array_map('trim', explode("\n", $opciones_raw));
    $opciones_json = esc_attr(json_encode($opciones_array));

    echo "<script 
        src='https://gerardonet.github.io/whatsapp-widget-netcommerce/whatsapp-widget.js' 
        defer 
        data-whatsapp='{$numero}' 
        data-email='{$correo}' 
        data-mostrar-servicio='{$mostrar_servicio}' 
        data-opciones-servicio='{$opciones_json}' 
        data-etiqueta-servicio='{$etiqueta_servicio}'
    ></script>";
});
