<?php
require 'config.php';
require 'functions.php';

// Har request par purani files delete karo (2 minutes se purani)
deleteOldFiles(UPLOAD_DIR, 120);

// Baaki download ka code yahin likho (woi jo pehle diya tha, file delete wali line hata ke)
$downloadError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_code'])) {
    $code = preg_replace('/[^a-f0-9]/', '', $_POST['download_code']);

    if (!$code) {
        $downloadError = 'Invalid code.';
    } else {
        $filePath = UPLOAD_DIR . $code . '.bin';

        if (!file_exists($filePath)) {
            $downloadError = 'File not found or expired.';
        } else {
            $data = file_get_contents($filePath);
            $parts = explode('::', $data);

            if (count($parts) !== 2) {
                $downloadError = 'Invalid file data.';
            } else {
                $encoded_data = $parts[0];
                $ext = $parts[1];

                $bin_data = base64_decode($encoded_data);

                if ($bin_data === false) {
                    $downloadError = 'Failed to decode data.';
                } else {
                    $iv = substr($bin_data, 0, 16);
                    $encryptedData = substr($bin_data, 16);

                    $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', ENCRYPTION_KEY, OPENSSL_RAW_DATA, $iv);

                    if ($decrypted === false) {
                        $downloadError = 'Decryption failed.';
                    } else {
                        // Serve file without deleting
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="tempfile.' . $ext . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . strlen($decrypted));
                        echo $decrypted;
                        exit;
                    }
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>TempShare - Secure Temporary File Sharing</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>

<!-- Navbar (same as before) -->
<nav class="navbar">
  <div class="nav-container">
    <a href="#" class="brand">
      <img src="download.svg" alt="TempShare Logo" class="logo" />
      <span class="site-title">TempShare</span>
    </a>
    <button class="nav-toggle" aria-label="Toggle menu" aria-expanded="false">
      <span class="hamburger"></span>
    </button>
    <ul class="nav-menu">
      <li><a href="#">Home</a></li>
      <li><a href="#how-to-use">About</a></li>
      <li class="dropdown">
        <a href="#" class="dropbtn" aria-haspopup="true" aria-expanded="false">Contact ▼</a>
        <ul class="dropdown-content" aria-label="Contact Links">
          <li><a href="https://instagram.com/yourinsta" target="_blank" rel="noopener">Instagram</a></li>
          <li><a href="https://github.com/yourgithub" target="_blank" rel="noopener">GitHub</a></li>
          <li><a href="https://t.me/yourtelegram" target="_blank" rel="noopener">Telegram</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<div class="page-container">

  <h1>Secure Temporary File Sharing</h1>


  <!-- Upload Form -->
  <form id="uploadForm" action="upload.php" method="POST" enctype="multipart/form-data">
    <label for="fileInput" class="custom-file-label">Choose file to upload</label>
    <input type="file" id="fileInput" name="file" required />
    <div id="fileSize"></div>
    <button type="submit">Upload File</button>
    <div class="loader" style="display:none;"></div>
  </form>

  <!-- Generated Code Box (only after upload) -->
  <?php if (isset($_GET['code'])): ?>
    <div class="code-box" style="margin-top: 30px;">
      <div id="codeText"><?=htmlspecialchars($_GET['code'])?></div>
      <div>
        <button id="copyCode">Copy Code</button>
        <button id="shareCode">Share Code</button>
      </div>
    </div>
  <?php endif; ?>

  <!-- Download (Enter Code) Form -->
  <section class="download-section" style="margin-top: 40px; user-select: text;">
    <h2>Enter Code to Download File</h2>
    <?php if ($downloadError): ?>
      <p style="color: #d32f2f; font-weight: 700; margin-bottom: 20px;"><?= htmlspecialchars($downloadError) ?></p>
    <?php endif; ?>
    <form action="" method="POST">
      <input type="text" name="download_code" placeholder="Enter your file code" required autocomplete="off" />
      <button type="submit">Download</button>
    </form>
  </section>

  <!-- How to use section as before... -->

</div>



 

  <!-- How to use -->
  <section class="howto-section" id="how-to-use">
    <h2>How to Use TempShare</h2>
    <ol>
      <li>Select any file (image, video, mp3, document etc.) and click "Upload File".</li>
      <li>Confirm upload in the popup.</li>
      <li>After upload, you will get a unique code. Share this code with the receiver.</li>
      <li>Receiver visits this site, enters the code below, and downloads the file in original format.</li>
      <li>Uploaded files are encrypted end-to-end and auto-deleted after 2 minutes for privacy.</li>
    </ol>
  </section>

</div>

<script src="script.js"></script>
<footer class="site-footer">
  &copy; <?=date('Y')?> Powered by
  <a href="https://t.me/GlitchX" target="_blank" rel="noopener">GlitchX</a> &amp;
  <a href="https://primescript.com" target="_blank" rel="noopener">PrimeScript</a>
</footer>

</body>
</html>
