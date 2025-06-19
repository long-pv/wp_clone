<?php
// xóa version hiển thị ở Front end
function remove_wp_version_strings($src)
{
    global $wp_version;
    $query_string = parse_url($src, PHP_URL_QUERY);
    if ($query_string) {
        parse_str($query_string, $query);
        if (!empty($query['ver']) && $query['ver'] === $wp_version) {
            $src = remove_query_arg('ver', $src);
        }
    }
    return $src;
}
add_filter('script_loader_src', 'remove_wp_version_strings');
add_filter('style_loader_src', 'remove_wp_version_strings');

// xóa version hiển thị ở Front end
function remove_version_wp()
{
    return '';
}
add_filter('the_generator', 'remove_version_wp');
// end

// Giới hạn loại tệp được tải lên
function restrict_file_types($mimes)
{
    $allowed_mime_types = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'mp4' => 'video/mp4',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'csv' => 'text/csv',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    );

    $mimes = array_intersect($allowed_mime_types, $mimes);

    return $mimes;
}
add_filter('upload_mimes', 'restrict_file_types');

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

// Vô hiệu hóa một số endpoints
add_filter('rest_endpoints', 'disable_default_endpoints');
function disable_default_endpoints($endpoints)
{
    $endpoints_to_remove = array(
        '/oembed/1.0',
        '/wp/v2/media',
        '/wp/v2/types',
        '/wp/v2/statuses',
        '/wp/v2/taxonomies',
        '/wp/v2/tags',
        '/wp/v2/users',
        '/wp/v2/comments',
        '/wp/v2/settings',
        '/wp/v2/themes',
        '/wp/v2/blocks',
        '/wp/v2/oembed',
        '/wp/v2/posts',
        '/wp/v2/pages',
        '/wp/v2/block-renderer',
        '/wp/v2/search',
        '/wp/v2/categories'
    );

    if (!is_user_logged_in() && !is_admin()) {
        foreach ($endpoints_to_remove as $rem_endpoint) {
            foreach ($endpoints as $maybe_endpoint => $object) {
                if (stripos($maybe_endpoint, $rem_endpoint) !== false) {
                    unset($endpoints[$maybe_endpoint]);
                }
            }
        }
    }

    return $endpoints;
}

// ngăn chặn truy cập vào trang tác giả để lấy thông tin
add_action('template_redirect', 'redirect_author_pages');
function redirect_author_pages()
{
    if (is_author()) {
        wp_redirect(home_url('/404'));
        exit();
    }
}

// xóa các style và script được nhập vào editor hiển thị ra ngoài FE
function remove_styles_and_scripts_from_content($content)
{
    $content = preg_replace('/<style[^>]*>.*?<\/style>/is', '', $content);
    $content = preg_replace('/<script[^>]*>.*?<\/script>/is', '', $content);

    return $content;
}
add_filter('the_content', 'remove_styles_and_scripts_from_content', 99);

// Xoá nút cho phép sử dụng mật khẩu yếu trong trang admin
add_action('admin_footer', 'remove_weak_password_button_script');
function remove_weak_password_button_script()
{
?>
    <script>
        jQuery(document).ready(function($) {
            $(".pw-weak").remove();
        });
    </script>
<?php
}

// người dùng chưa đăng nhập sẽ k truy cập vào /wp-admin
add_action('init', 'custom_block_wp_admin_access');
function custom_block_wp_admin_access()
{
    if (!is_admin() || is_user_logged_in()) {
        return;
    }

    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }

    wp_die(
        '<h1>Access Denied</h1>
         <p>You do not have permission to access the admin area. Please log in to continue.</p>
         <p>
            <a href="' . home_url() . '" style="display:inline-block;margin-top:10px;padding:8px 16px;background:#0073aa;color:#fff;text-decoration:none;border-radius:4px;">
                Back to Homepage
            </a>
         </p>',
        'Unauthorized Access',
        array('response' => 403)
    );
}
