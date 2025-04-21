<?php
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