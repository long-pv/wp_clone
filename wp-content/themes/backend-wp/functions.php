<?php
// Setup theme setting page
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(
        array(
            'page_title' => 'Theme Settings',
            'menu_title' => 'Theme Settings',
            'menu_slug' => 'theme-settings',
            'capability' => 'edit_posts',
            'redirect' => false,
            'position' => 80
        )
    );
}

// Block CORS in WordPress
add_action('init', 'add_cors_http_header');
add_action('send_headers', 'add_cors_http_header');
function add_cors_http_header()
{
    header("Access-Control-Allow-Origin: *");
    header("X-Powered-By: none");
}

function cl_customize_rest_cors()
{
    remove_filter('rest_pre_serve_request', 'rest_send_cors_headers');
    add_filter('rest_pre_serve_request', function ($value) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Expose-Headers: Link', false);
        return $value;
    });
}
add_action('rest_api_init', 'cl_customize_rest_cors', 15);

// all field options
add_action("rest_api_init", function () {
    register_rest_route("v3", "/options", [
        "methods" => "GET",
        "callback" => "acf_options_route",
        "permission_callback" => "__return_true",
    ]);
});

function acf_options_route()
{
    if (!function_exists('get_fields')) {
        return new WP_Error('acf_missing', 'ACF plugin is not active.', array('status' => 500));
    }

    return get_fields('options');
}
