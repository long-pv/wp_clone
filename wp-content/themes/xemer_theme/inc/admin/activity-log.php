<?php
// Tạo bảng log nếu chưa có
function ual_maybe_create_table()
{
    global $wpdb;
    $table = $wpdb->prefix . 'user_activity_log';
    $exists = $wpdb->get_var("SHOW TABLES LIKE '$table'") === $table;

    if (!$exists) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED,
            username VARCHAR(60),
            action VARCHAR(100),
            object_id BIGINT,
            object_title TEXT,
            ip_address VARCHAR(45),
            log_time DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
add_action('admin_init', 'ual_maybe_create_table');
add_action('init', 'ual_maybe_create_table');

// Hàm ghi log
function ual_log_activity($action, $object_id = null, $object_title = null)
{
    if (!is_user_logged_in()) return;

    global $wpdb;
    $user = wp_get_current_user();
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    $wpdb->insert($wpdb->prefix . 'user_activity_log', [
        'user_id' => $user->ID,
        'username' => $user->user_login,
        'action' => $action,
        'object_id' => $object_id,
        'object_title' => $object_title,
        'ip_address' => $ip,
        'log_time' => current_time('mysql')
    ]);
}

// Ghi log khi đăng nhập
add_action('wp_login', function ($user_login, $user) {
    wp_set_current_user($user->ID);
    ual_log_activity('login');
}, 10, 2);

// Ghi log khi đăng xuất
add_action('clear_auth_cookie', function () {
    if (is_user_logged_in()) {
        ual_log_activity('logout');
    }
});

// Ghi log khi tạo/sửa bài viết
add_action('save_post', function ($post_id, $post, $update) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) return;

    $action = $update ? 'update_post' : 'create_post';
    ual_log_activity($action, $post_id, get_the_title($post_id));
}, 10, 3);

// Ghi log khi xóa bài viết
add_action('before_delete_post', function ($post_id) {
    $title = get_the_title($post_id);
    ual_log_activity('delete_post', $post_id, $title);
});

// Giao diện admin
add_action('admin_menu', function () {
    add_menu_page('User Activity Log', 'Activity Log', 'manage_options', 'user-activity-log', 'ual_render_log_page', 'dashicons-list-view', 80);
});

// Render bảng log (không có cột ID)
function ual_render_log_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'user_activity_log';
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    $logs = $wpdb->get_results("SELECT * FROM $table ORDER BY log_time DESC LIMIT $offset, $per_page");

    echo '<div class="wrap"><h1>User Activity Log</h1>';
    echo '<table class="widefat fixed striped"><thead><tr>';
    echo '<th>User</th><th>Action</th><th>Post</th><th>IP</th><th>Time</th>';
    echo '</tr></thead><tbody>';

    if ($logs) {
        foreach ($logs as $log) {
            echo '<tr>';
            echo '<td>' . esc_html($log->username . ' (#' . $log->user_id . ')') . '</td>';
            echo '<td>' . esc_html($log->action) . '</td>';
            echo '<td>';
            if ($log->object_id && $log->object_title) {
                echo '<a href="' . esc_url(get_edit_post_link($log->object_id)) . '">' . esc_html($log->object_title) . '</a>';
            } else {
                if ($log->object_title) {
                    echo esc_html($log->object_title);
                } else {
                    echo '-';
                }
            }
            echo '</td>';
            echo '<td>' . esc_html($log->ip_address) . '</td>';
            echo '<td>' . esc_html($log->log_time) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="5">Không có log nào.</td></tr>';
    }

    echo '</tbody></table>';

    // Phân trang
    $total_pages = ceil($total_items / $per_page);
    if ($total_pages > 1) {
        echo '<div class="tablenav"><div class="tablenav-pages">';
        $base_url = admin_url('admin.php?page=user-activity-log');
        for ($i = 1; $i <= $total_pages; $i++) {
            $link = esc_url(add_query_arg('paged', $i, $base_url));
            $current = $i === $current_page ? ' style="font-weight: bold;"' : '';
            echo "<a href='$link'$current>$i</a> ";
        }
        echo '</div></div>';
    }

    echo '</div>';
}

// Ghi log khi plugin được kích hoạt
add_action('activated_plugin', function ($plugin) {
    ual_log_activity('plugin_activated', null, $plugin);
});

// Ghi log khi plugin bị vô hiệu hóa
add_action('deactivated_plugin', function ($plugin) {
    ual_log_activity('plugin_deactivated', null, $plugin);
});

// Ghi log khi plugin bị xóa (chỉ hoạt động WP 5.5+)
add_action('deleted_plugin', function ($plugin) {
    ual_log_activity('plugin_deleted', null, $plugin);
});
