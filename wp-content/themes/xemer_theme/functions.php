<?php
require get_template_directory() . '/inc/loader.php';
// load widgets library by elementor
function load_custom_widgets()
{
    require get_template_directory() . '/widgets/index.php';
}
add_action('elementor/init', 'load_custom_widgets');
