<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error.');
    }

    $file = $_FILES['file'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    $data = file_get_contents($file['tmp_name']);

    $iv = random_bytes(16);

    // Encrypt using your key from config.php
    $encryptedData = openssl_encrypt($data, 'AES-256-CBC', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);

    if ($encryptedData === false) {
        die('Encryption failed.');
    }

    // Combine IV and encrypted data, then base64 encode
    $bin_data = $iv . $encryptedData;
    $encoded_data = base64_encode($bin_data);

    // Generate random code (12 hex chars)
    $code = bin2hex(random_bytes(6));
    $filePath = UPLOAD_DIR . $code . '.bin';

    // Save encoded data and extension separated by ::
    file_put_contents($filePath, $encoded_data . '::' . $ext);

    // Redirect to index.php with code parameter to show code
    header('Location: index.php?code=' . $code);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['uploaded_file'])) {
        echo "File upload nahi hua.";
        exit;
    }

    $error = $_FILES['uploaded_file']['error'];
    if ($error !== UPLOAD_ERR_OK) {
        echo "Upload error code: " . $error;
        exit;
    }

    // Agar yahan aap file move kar rahe ho to uska code bhi bhejein.
}
?>
