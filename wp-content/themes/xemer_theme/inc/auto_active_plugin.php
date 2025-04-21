<?php
function activate_my_plugins()
{
    $plugins = [
        'advanced-custom-fields-pro\acf.php',
        'classic-editor\classic-editor.php',
        'all-in-one-wp-migration-master\all-in-one-wp-migration.php',
        'duplicate-page\duplicatepage.php',
    ];

    foreach ($plugins as $plugin) {
        $plugin_path = WP_PLUGIN_DIR . '/' . $plugin;

        if (file_exists($plugin_path) && !is_plugin_active($plugin)) {
            activate_plugin($plugin);
        }
    }
}
add_action('admin_init', 'activate_my_plugins');

// stop upgrading wp cerber plugin
add_filter('site_transient_update_plugins', 'disable_plugins_update');
function disable_plugins_update($value)
{
    // disable acf pro
    if (isset($value->response['advanced-custom-fields-pro/acf.php'])) {
        unset($value->response['advanced-custom-fields-pro/acf.php']);
    }

    // disable All-in-One WP Migration
    if (isset($value->response['all-in-one-wp-migration-master/all-in-one-wp-migration.php'])) {
        unset($value->response['all-in-one-wp-migration-master/all-in-one-wp-migration.php']);
    }
    return $value;
}
