<?php

/**
 * Enqueue scripts and styles.
 */
function xemer_theme_scripts()
{
    // bootstrap js
    wp_enqueue_script('xemer_theme-script-bootstrap_bundle', get_template_directory_uri() . '/assets/inc/bootstrap/bootstrap.bundle.min.js', array('jquery'), _S_VERSION, true);

    // matchHeight
    wp_enqueue_script('xemer_theme-script-matchHeight', get_template_directory_uri() . '/assets/inc/matchHeight/jquery.matchHeight.js', array('jquery'), _S_VERSION, true);

    // validate
    wp_enqueue_script('xemer_theme-script-validate', get_template_directory_uri() . '/assets/inc/validate/validate.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('xemer_theme-script-validate_custom', get_template_directory_uri() . '/assets/js/validate.js', array('jquery'), _S_VERSION, true);

    // wow - effect
    wp_enqueue_style('xemer_theme-style-wow', get_template_directory_uri() . '/assets/inc/wow/wow.css', array(), _S_VERSION);
    wp_enqueue_script('xemer_theme-script-wow', get_template_directory_uri() . '/assets/inc/wow/wow.js', array('jquery'), _S_VERSION, true);
    wp_enqueue_script('xemer_theme-script-wow_custom', get_template_directory_uri() . '/assets/inc/wow/index.js', array('jquery'), _S_VERSION, true);
    // end

    // select2
    wp_enqueue_style('xemer_theme-style-select2', get_template_directory_uri() . '/assets/inc/select2/select2.css', array(), _S_VERSION);
    wp_enqueue_script('xemer_theme-script-select2', get_template_directory_uri() . '/assets/inc/select2/select2.js', array('jquery'), _S_VERSION, true);

    // fancybox
    wp_enqueue_style('xemer_theme-style-select2', get_template_directory_uri() . '/assets/inc/fancybox/fancybox.css', array(), _S_VERSION);
    wp_enqueue_script('xemer_theme-script-select2', get_template_directory_uri() . '/assets/inc/fancybox/fancybox.js', array('jquery'), _S_VERSION, true);

    // slick
    wp_enqueue_style('xemer_theme-style-slick-theme', get_template_directory_uri() . '/assets/inc/slick/slick-theme.css', array(), _S_VERSION);
    wp_enqueue_style('xemer_theme-style-slick', get_template_directory_uri() . '/assets/inc/slick/slick.css', array(), _S_VERSION);
    wp_enqueue_script('xemer_theme-script-slick', get_template_directory_uri() . '/assets/inc/slick/slick.min.js', array('jquery'), _S_VERSION, true);

    //add custom main css/js
    $main_css_file_path = get_template_directory() . '/assets/css/main.css';
    $main_js_file_path = get_template_directory() . '/assets/js/main.js';
    $ver_main_css = file_exists($main_css_file_path) ? filemtime($main_css_file_path) : _S_VERSION;
    $ver_main_js = file_exists($main_js_file_path) ? filemtime($main_js_file_path) : _S_VERSION;
    wp_enqueue_style('xemer_theme-style-main', get_template_directory_uri() . '/assets/css/main.css', array(), $ver_main_css);
    wp_enqueue_script('xemer_theme-script-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), $ver_main_js, true);
}
add_action('wp_enqueue_scripts', 'xemer_theme_scripts');
