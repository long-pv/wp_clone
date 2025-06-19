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

// Xóa nút "Xem" khỏi các hành động hàng của người dùng
function remove_user_row_actions($actions)
{
    unset($actions['view']);
    return $actions;
}
add_filter('user_row_actions', 'remove_user_row_actions', 10, 1);

// cho phép tất cả role user được nhập đầy đủ các Tags
function allow_role_fill_all_tag($allowedposttags)
{
    // Allow iframe embed tags exclusively
    $allowedposttags["iframe"] = array(
        "src" => true,
        "width" => true,
        "height" => true,
        "class" => true,
        "frameborder" => true,
        "webkitAllowFullScreen" => true,
        "mozallowfullscreen" => true,
        "allowFullScreen" => true,
        "allow" => true,
    );

    // tag's allowable attribute
    $allowed_atts = array(
        'type' => array(),
        'align' => array(),
        'class' => array(),
        'id' => array(),
        'dir' => array(),
        'lang' => array(),
        'style' => array(),
        'xml:lang' => array(),
        'src' => array(),
        'alt' => array(),
        'href' => array(),
        'rel' => array(),
        'rev' => array(),
        'target' => array(),
        'novalidate' => array(),
        'value' => array(),
        'name' => array(),
        'tabindex' => array(),
        'action' => array(),
        'method' => array(),
        'for' => array(),
        'width' => array(),
        'height' => array(),
        'data' => array(),
        'title' => array(),
    );

    // list of tags saved to db
    $allowedposttags["center"] = $allowed_atts;
    $allowedposttags['form'] = $allowed_atts;
    $allowedposttags['label'] = $allowed_atts;
    $allowedposttags['input'] = $allowed_atts;
    $allowedposttags['textarea'] = $allowed_atts;
    $allowedposttags['iframe'] = $allowed_atts;
    $allowedposttags['script'] = $allowed_atts;
    $allowedposttags['style'] = $allowed_atts;
    $allowedposttags['strong'] = $allowed_atts;
    $allowedposttags['small'] = $allowed_atts;
    $allowedposttags['table'] = $allowed_atts;
    $allowedposttags['span'] = $allowed_atts;
    $allowedposttags['abbr'] = $allowed_atts;
    $allowedposttags['code'] = $allowed_atts;
    $allowedposttags['pre'] = $allowed_atts;
    $allowedposttags['div'] = $allowed_atts;
    $allowedposttags['img'] = $allowed_atts;
    $allowedposttags['h1'] = $allowed_atts;
    $allowedposttags['h2'] = $allowed_atts;
    $allowedposttags['h3'] = $allowed_atts;
    $allowedposttags['h4'] = $allowed_atts;
    $allowedposttags['h5'] = $allowed_atts;
    $allowedposttags['h6'] = $allowed_atts;
    $allowedposttags['ol'] = $allowed_atts;
    $allowedposttags['ul'] = $allowed_atts;
    $allowedposttags['li'] = $allowed_atts;
    $allowedposttags['em'] = $allowed_atts;
    $allowedposttags['hr'] = $allowed_atts;
    $allowedposttags['br'] = $allowed_atts;
    $allowedposttags['tr'] = $allowed_atts;
    $allowedposttags['td'] = $allowed_atts;
    $allowedposttags['p'] = $allowed_atts;
    $allowedposttags['a'] = $allowed_atts;
    $allowedposttags['b'] = $allowed_atts;
    $allowedposttags['i'] = $allowed_atts;

    return $allowedposttags;
}
add_filter("wp_kses_allowed_html", "allow_role_fill_all_tag", 1);
