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

// tối đa revision
add_filter('wp_revisions_to_keep', function ($num, $post) {
    return 3;
}, 10, 2);

// thêm thương hiệu
function xemer_theme_custom_admin_footer()
{
    echo 'Thanks for using WordPress. Powered by <a target="_blank" href="https://tramkienthuc.net/">Xemer Theme</a>.';
}
add_filter('admin_footer_text', 'xemer_theme_custom_admin_footer');

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

// ẩn logo mặc định trên trang đăng nhập
function custom_login_logo()
{
    echo '<style type="text/css">#login h1 a {display: none !important;}</style>';
}
add_action('login_head', 'custom_login_logo');

// validate tiêu đề các bài viết
add_action('admin_footer', 'validate_title_post_admin');
function validate_title_post_admin()
{
?>
    <script>
        jQuery(document).ready(function($) {
            // Validate post title
            if ($('#post').length > 0) {
                $('#post').submit(function() {
                    var title_post = $('#title').val();
                    if (title_post.trim() === '') {
                        alert('Please enter "Title".');
                        $('#title').focus();
                        return false;
                    }
                });
            }
        });
    </script>
<?php
}

//  giới hạn kích thước file tải lên
function custom_upload_size_limit($bytes)
{
    return 20 * 1024 * 1024;
}
add_filter('upload_size_limit', 'custom_upload_size_limit');

// The function "write_log" is used to write debug logs to a file in PHP.
function write_log($log = null, $title = 'Debug')
{
    if ($log) {
        if (is_array($log) || is_object($log)) {
            $log = print_r($log, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $text = '[' . $timestamp . '] : ' . $title . ' - Log: ' . $log . "\n";
        $log_file = WP_CONTENT_DIR . '/debug.log';
        $file_handle = fopen($log_file, 'a');
        fwrite($file_handle, $text);
        fclose($file_handle);
    }
}
