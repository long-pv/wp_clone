<?php
// turn on auto update core wp
define('WP_AUTO_UPDATE_CORE', true); // Bật cập nhật tự động WordPress
define('AUTOMATIC_UPDATER_DISABLED', false); // Đảm bảo cập nhật tự động không bị tắt
define('WP_AUTO_UPDATE_PLUGINS', true); // Kích hoạt cập nhật tự động cho Plugin
define('WP_AUTO_UPDATE_THEMES', true); // Kích hoạt cập nhật tự động cho Theme
add_filter('auto_update_plugin', '__return_true'); // Tự động cập nhật plugin
add_filter('auto_update_theme', '__return_true'); // Tự động cập nhật theme
add_filter('auto_update_core', '__return_true'); // Tự động cập nhật WordPress core (cả bản lớn & nhỏ)