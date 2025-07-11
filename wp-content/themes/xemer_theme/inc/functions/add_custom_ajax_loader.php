<?php
add_action('wp_footer', 'add_custom_ajax_loader');

function add_custom_ajax_loader()
{
?>
    <div id="ajax-loader" style="display: none;">
        <div class="spinner"></div>
    </div>
<?php
}
