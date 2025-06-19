<?php

/**
 * Add Recommended size image to Featured Image Box    
 */
add_filter('admin_post_thumbnail_html', 'add_featured_image_instruction');
function add_featured_image_instruction($html)
{
    $post_type = get_post_type() ?? 'post';
    if ($post_type == 'post') {
        $html .= '<p>Recommended size: 300x300</p>';
    }

    return $html;
}
