<?php
class WP_GitHub_Updater {
    private $plugin_file;
    private $github_api = 'https://api.github.com/repos/gerardonet/whatsapp-float-form/releases/latest';

    public function __construct($plugin_file) {
        $this->plugin_file = $plugin_file;
        add_filter('pre_set_site_transient_update_plugins', [$this, 'check_for_update']);
        add_filter('plugins_api', [$this, 'plugin_info'], 10, 3);
    }

    public function check_for_update($transient) {
        if (empty($transient->checked)) return $transient;

        $remote = $this->get_remote_info();
        if (!$remote || version_compare($remote->tag_name, $this->get_version(), '<=')) return $transient;

        $plugin_slug = plugin_basename($this->plugin_file);
        $transient->response[$plugin_slug] = (object)[
            'slug' => dirname($plugin_slug),
            'new_version' => $remote->tag_name,
            'url' => $remote->html_url,
            'package' => $remote->zipball_url,
        ];

        return $transient;
    }

    public function plugin_info($res, $action, $args) {
        if ($action !== 'plugin_information') return $res;

        $plugin_slug = plugin_basename($this->plugin_file);
        if ($args->slug !== dirname($plugin_slug)) return false;

        $remote = $this->get_remote_info();
        if (!$remote) return false;

        return (object)[
            'name' => 'WhatsApp Float Form',
            'slug' => dirname($plugin_slug),
            'version' => $remote->tag_name,
            'author' => '<a href="https://netcommerce.mx">Gerardo Murillo</a>',
            'homepage' => $remote->html_url,
            'download_link' => $remote->zipball_url,
            'sections' => [
                'description' => 'Formulario flotante con integración a WhatsApp.',
            ],
        ];
    }

    private function get_remote_info() {
    // Intenta obtener la información desde caché (transitorio)
    $cached = get_transient('wff_latest_release');
    if ($cached !== false) return $cached;

    // Si no hay caché, llama a la API de GitHub
    $response = wp_remote_get($this->github_api, [
        'headers' => ['User-Agent' => 'WordPress Plugin Updater']
    ]);

    // Si hubo error o respuesta no válida, salir
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) return false;

    // Decodifica la respuesta JSON
    $release = json_decode(wp_remote_retrieve_body($response));

    // Guarda en caché por 1 hora
    set_transient('wff_latest_release', $release, HOUR_IN_SECONDS);

    return $release;
}

    private function get_version() {
        if (!function_exists('get_plugin_data')) require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $data = get_plugin_data($this->plugin_file);
        return $data['Version'];
    }
}
