document.addEventListener('DOMContentLoaded', () => {
  // Copy Code Button
  const copyBtn = document.getElementById('copyCode');
  if (copyBtn) {
    copyBtn.addEventListener('click', () => {
      const codeTextElem = document.getElementById('codeText');
      if (!codeTextElem) return alert('Code not found.');

      const textToCopy = codeTextElem.textContent.trim();
      if (!textToCopy) return alert('Nothing to copy.');

      if (navigator.clipboard && window.isSecureContext) {
        // Modern API
        navigator.clipboard.writeText(textToCopy).then(() => {
          copyBtn.textContent = 'Copied!';
          setTimeout(() => (copyBtn.textContent = 'Copy Code'), 2000);
        }).catch(() => alert('Copy failed! Try manually.'));
      } else {
        // Fallback older method
        try {
          const textarea = document.createElement('textarea');
          textarea.value = textToCopy;
          textarea.style.position = 'fixed'; // Avoid scrolling to bottom
          textarea.style.left = '-9999px';
          document.body.appendChild(textarea);
          textarea.focus();
          textarea.select();
          const successful = document.execCommand('copy');
          document.body.removeChild(textarea);
          if (successful) {
            copyBtn.textContent = 'Copied!';
            setTimeout(() => (copyBtn.textContent = 'Copy Code'), 2000);
          } else {
            alert('Copy failed! Try manually.');
          }
        } catch {
          alert('Copy not supported in this browser.');
        }
      }
    });
  }

  // Share Code Button
  const shareBtn = document.getElementById('shareCode');
  if (shareBtn) {
    shareBtn.addEventListener('click', async () => {
      const codeTextElem = document.getElementById('codeText');
      if (!codeTextElem) return alert('Code not found.');

      const codeText = codeTextElem.textContent.trim();
      if (!codeText) return alert('Nothing to share.');

      if (navigator.share) {
        try {
          await navigator.share({
            title: 'TempShare File Code',
            text: `Use this code to download your file: ${codeText}`,
            url: window.location.href,
          });
        } catch (err) {
          // User cancelled or error
          console.log('Share failed:', err);
        }
      } else {
        alert('Sharing not supported on this browser. Please copy the code instead.');
      }
    });
  }
});
