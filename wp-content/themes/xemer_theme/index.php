<?php
wp_die(
    'You do not have permission to access this page. Please <a href="' . home_url() . '">return to the homepage</a>.',
    'Access Denied',
    array('response' => 403)
);
