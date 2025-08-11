<?php
// config.php

// Encryption key (random 32 chars hex string for AES-256)
define('ENCRYPTION_KEY', hex2bin('0123456789abcdef0123456789abcdef0123456789abcdef0123456789abcdef')); 

// Upload folder path
define('UPLOAD_DIR', __DIR__ . '/uploads/');

// URL base for generating download links
define('BASE_URL', 'https://temp-share-jclf.onrender.com'); // Change this to your actual site URL

