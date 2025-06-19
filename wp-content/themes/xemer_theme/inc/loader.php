<?php
// ===== CORE =====
require_once get_template_directory() . '/inc/core/setup.php';
require_once get_template_directory() . '/inc/core/enqueue.php';
require_once get_template_directory() . '/inc/core/security.php';
require_once get_template_directory() . '/inc/core/auto-update-wp.php';
require_once get_template_directory() . '/inc/core/auto-active-plugin.php';
require_once get_template_directory() . '/inc/core/settings.php';

// ===== ADMIN =====
require_once get_template_directory() . '/inc/admin/index.php';
require_once get_template_directory() . '/inc/admin/size-image.php';

// ===== CUSTOM POSTS =====
require_once get_template_directory() . '/inc/custom-posts/cpt_custom.php';

// ===== FUNCTIONS =====
require_once get_template_directory() . '/inc/functions/breadcrumbs.php';
require_once get_template_directory() . '/inc/functions/shortcodes.php';

// ===== PLUGINS =====
require_once get_template_directory() . '/inc/plugins/acf.php';
require_once get_template_directory() . '/inc/plugins/polylang.php';
require_once get_template_directory() . '/inc/plugins/contact-form-7.php';

// ===== AJAX =====
require_once get_template_directory() . '/inc/ajax/form-handler.php';
require_once get_template_directory() . '/inc/ajax/product-filter.php';

// ===== WOOCOMMERCE =====
require_once get_template_directory() . '/inc/woo/cart.php';
require_once get_template_directory() . '/inc/woo/checkout.php';
require_once get_template_directory() . '/inc/woo/global.php';
require_once get_template_directory() . '/inc/woo/mail.php';
require_once get_template_directory() . '/inc/woo/myaccount.php';
require_once get_template_directory() . '/inc/woo/shop.php';
require_once get_template_directory() . '/inc/woo/single-product.php';
require_once get_template_directory() . '/inc/woo/thankyou.php';
