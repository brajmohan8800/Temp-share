<?php
require 'config.php';

$code = isset($_GET['code']) ? strtoupper(trim($_GET['code'])) : '';
$filePath = UPLOAD_DIR . $code . '.enc';

if ($code && file_exists($filePath)) {
    $key = ENCRYPTION_KEY;
    $data = file_get_contents($filePath);
    $ivlen = openssl_cipher_iv_length('AES-256-CBC');
    $iv = substr($data, 0, $ivlen);
    $ciphertext = substr($data, $ivlen);
    $plaintext = openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    if ($plaintext === false) {
        http_response_code(500);
        exit;
    }

    $metaFile = UPLOAD_DIR . $code . '.meta';
    $ext = 'dat';

    if (file_exists($metaFile)) {
        $ext = file_get_contents($metaFile);
    }

    // Serve inline content with correct mime type for image/video
    $mimeTypes = [
      'jpg' => 'image/jpeg',
      'jpeg' => 'image/jpeg',
      'png' => 'image/png',
      'gif' => 'image/gif',
      'mp4' => 'video/mp4',
      'mov' => 'video/quicktime',
    ];
    $extLower = strtolower($ext);
    $contentType = $mimeTypes[$extLower] ?? 'application/octet-stream';

    header('Content-Type: ' . $contentType);
    echo $plaintext;
    exit;
} else {
    http_response_code(404);
    echo "Preview not found.";
}
