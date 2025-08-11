<?php
function deleteOldFiles($folder, $expiry_seconds = 120) {
    foreach (glob($folder . '*') as $file) {
        if (is_file($file)) {
            if (time() - filemtime($file) > $expiry_seconds) {
                unlink($file);
            }
        }
    }
}
