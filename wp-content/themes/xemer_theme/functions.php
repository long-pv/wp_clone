<?php
if (!defined('_S_VERSION')) {
    define('_S_VERSION', '1.0.0');
}

// load php include
require get_template_directory() . '/inc/loader.php';

// load widgets library by elementor
function load_custom_widgets()
{
    require get_template_directory() . '/widgets/index.php';
}
add_action('elementor/init', 'load_custom_widgets');
