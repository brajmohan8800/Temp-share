<?php
require 'config.php';

$files = glob(UPLOAD_DIR . '*.bin');
$now = time();

foreach ($files as $file) {
    if (is_file($file)) {
        if ($now - filemtime($file) > 120) { // 120 seconds = 2 mins
            unlink($file);
        }
    }
}
?>
