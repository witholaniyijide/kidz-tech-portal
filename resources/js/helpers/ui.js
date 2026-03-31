export async function copyToClipboard(text) {
    // Try modern Clipboard API first (requires HTTPS)
    if (navigator.clipboard && window.isSecureContext) {
        try {
            await navigator.clipboard.writeText(text);
            alert('Copied to clipboard!');
            return;
        } catch (error) {
            console.error('Clipboard API failed:', error);
        }
    }

    // Fallback for HTTP sites or older browsers
    try {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-9999px';
        textArea.style.top = '-9999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();

        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);

        if (successful) {
            alert('Copied to clipboard!');
        } else {
            alert('Failed to copy. Please select the text and copy manually (Ctrl+C).');
        }
    } catch (error) {
        console.error('Fallback copy failed:', error);
        alert('Failed to copy. Please select the text and copy manually (Ctrl+C).');
    }
}
