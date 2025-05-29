<?php
/*
Plugin Name: WhatsApp Float Form
Description: Formulario flotante con integración a WhatsApp.
Version: 1.0.0
Author: Gerardo Murillo
*/

if (!defined('ABSPATH')) exit;

// Cargar HTML + JS del formulario
require_once plugin_dir_path(__FILE__) . 'wff-html.php';

// Cargar clase de actualización desde GitHub
require_once plugin_dir_path(__FILE__) . 'updater.php';
new WP_GitHub_Updater(__FILE__);
