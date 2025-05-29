<?php
/**
 * Plugin Name: WhatsApp Float Form
 * Description: Botón flotante con formulario que envía mensaje por WhatsApp y correo con Mailgun.
 * Version: 1.0
 * Author: Tu Nombre
 */

add_action('wp_footer', 'wff_insert_floating_form');
add_action('wp_enqueue_scripts', 'wff_enqueue_assets');

function wff_enqueue_assets() {
    wp_enqueue_style('wff-style', plugin_dir_url(__FILE__) . 'wff-style.css');
    wp_enqueue_script('wff-script', plugin_dir_url(__FILE__) . 'wff-script.js', [], false, true);
}

function wff_insert_floating_form() {
    include plugin_dir_path(__FILE__) . 'wff-html.php';
}

add_action('wp_ajax_wff_send_email', 'wff_send_email');
add_action('wp_ajax_nopriv_wff_send_email', 'wff_send_email');

function wff_send_email() {
    $to = get_option('admin_email');
    $subject = 'Nuevo mensaje desde tu sitio web';
    $body = "Hola, me gustaría más información.\n"
          . "Me llamo: " . sanitize_text_field($_POST['nombre']) . "\n"
          . "Mi correo es: " . sanitize_email($_POST['email']) . "\n"
          . "Mi teléfono: " . sanitize_text_field($_POST['telefono']) . "\n"
          . "Servicio de interés: " . sanitize_text_field($_POST['servicio']) . "\n"
          . "Mensaje: " . sanitize_textarea_field($_POST['mensaje']) . "\n";

    wp_mail($to, $subject, $body);

    wp_send_json_success('Correo enviado correctamente.');
}
