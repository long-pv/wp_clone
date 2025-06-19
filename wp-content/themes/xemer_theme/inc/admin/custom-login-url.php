<?php
// Tạo đường dẫn mới để đăng nhập: /backend
add_action('init', function () {
    add_rewrite_rule('^backend/?$', 'wp-login.php', 'top');
}, 10, 0);

// Flush rewrite rules khi switch theme
add_action('after_switch_theme', function () {
    flush_rewrite_rules();
});

// Chặn truy cập wp-login.php nếu không hợp lệ
add_action('login_init', function () {
    $request_uri = $_SERVER['REQUEST_URI'];

    // Cho phép nếu là POST login hợp lệ
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($request_uri, '/wp-login.php') !== false) {
        return;
    }

    // Cho phép nếu là AJAX hoặc REST
    if (defined('DOING_AJAX') || defined('REST_REQUEST')) {
        return;
    }

    // Cho phép nếu là hành động logout
    if (isset($_GET['action']) && $_GET['action'] === 'logout') {
        return;
    }

    // Chặn action không cho phép
    if (
        isset($_GET['action']) &&
        in_array($_GET['action'], ['lostpassword', 'retrievepassword', 'resetpass', 'rp', 'register'])
    ) {
        wp_die(
            '<h1>Access Denied</h1><p>Password recovery and registration are disabled.</p>',
            'Unauthorized Access',
            array('response' => 403)
        );
    }

    // Chặn nếu truy cập trực tiếp wp-login.php (không qua /backend)
    if (
        strpos($request_uri, '/wp-login.php') !== false &&
        strpos($request_uri, '/backend') === false
    ) {
        wp_die(
            '<h1>Access Denied</h1><p>You are not allowed to access this page.</p>',
            'Unauthorized Access',
            array('response' => 403)
        );
    }
});

// Thay form action trong trang login để dùng /backend thay vì /wp-login.php
add_filter('site_url', function ($url, $path, $scheme, $blog_id) {
    if ($path === 'wp-login.php' || $path === '/wp-login.php') {
        return site_url('backend', $scheme);
    }
    return $url;
}, 10, 4);

// Chuyển hướng sau khi logout về trang chủ (hoặc về /backend nếu muốn)
add_filter('logout_redirect', function ($redirect_to, $requested_redirect_to, $user) {
    return site_url('backend');
}, 10, 3);

// Ẩn "Lost your password?" và "Go to site" trong trang login
add_action('login_footer', function () {
?>
    <style>
        .login #nav,
        .login #backtoblog {
            display: none !important;
        }
    </style>
<?php
});
