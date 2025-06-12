<?php
function register_cpt_post_types()
{
    $cpt_list = [
        // 'event' => [
        //     'labels' => __('Event', 'basetheme'),
        //     'slug' => 'su_kien',
        //     'cap' => false,
        //     'hierarchical' => false,
        //     'position' => false,
        //     'icon' => 'dashicons-calendar'
        // ],
    ];

    $cpt_tax = [
        // 'event_category' => [
        //     'labels' => __('Event category', 'basetheme'),
        //     'slug' => 'danh-muc-su-kien',
        //     'cap' => false,
        //     'post_type' => ['event']
        // ],
    ];

    foreach ($cpt_list as $post_type => $data) {
        register_cpt($post_type, $data);
    }

    foreach ($cpt_tax as $ctx => $data) {
        register_ctx($ctx, $data);
    }
}
add_action('init', 'register_cpt_post_types');

function register_cpt($post_type, $data = [])
{
    $hierarchical = !empty($data['hierarchical']) ? $data['hierarchical'] : false;
    $position = !empty($data['position']) ? $data['position'] : 30;
    $attributes = $hierarchical == true ? 'page-attributes' : '';
    $icon = !empty($data['icon']) ? $data['icon'] : 'dashicons-admin-post';

    $labels = [
        'name' => $data['labels'],
        'singular_name' => $data['labels'],
        'menu_name' => $data['labels'],
    ];

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'rewrite' => array(
            'slug' => $data['slug'] ?? $post_type,
            'with_front' => false,
            'hierarchical' => true,
        ),
        'supports' => array('title', 'editor', 'thumbnail', 'revisions', 'author', $attributes),
        'show_in_nav_menus' => true,
        'show_ui' => true,
        'menu_icon' => $icon,
        'archive_title' => $data['labels'],
        'menu_position' => $position,
    );

    if (!empty($data['tax'])) {
        $args['taxonomies'] = $data['tax'];
    }

    if (!empty($data['cap'])) {
        $capabilities = [
            'create_posts' => 'create_' . $post_type,
            'delete_others_posts' => 'delete_' . $post_type,
            'delete_posts' => 'delete_' . $post_type,
            'delete_private_posts' => 'delete_private_' . $post_type,
            'delete_published_posts' => 'delete_published_' . $post_type,
            'edit_others_posts' => 'edit_others_' . $post_type,
            'edit_posts' => 'edit_' . $post_type,
            'edit_private_posts' => 'edit_private_' . $post_type,
            'edit_published_posts' => 'edit_published_' . $post_type,
            'publish_posts' => 'publish_' . $post_type,
            'read_private_posts' => 'read_private_' . $post_type,
        ];
        $args['capabilities'] = $capabilities;
    }

    register_post_type($post_type, $args);
}

function register_ctx($ctx, $data)
{
    $labels = [
        'name' => $data['labels'],
        'singular_name' => $data['labels'],
    ];

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array(
            'slug' => $data['slug'] ?? $ctx,
            'with_front' => false,
            'hierarchical' => true,
        ),
    );

    if (!empty($data['cap'])) {
        $capabilities = [
            'manage_terms' => 'manage_' . $ctx,
            'edit_terms' => 'edit_' . $ctx,
            'delete_terms' => 'delete_' . $ctx,
            'assign_terms' => 'assign_' . $ctx,
        ];
        $args['capabilities'] = $capabilities;
    }

    register_taxonomy($ctx, $data['post_type'], $args);
}
