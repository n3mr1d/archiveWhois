// DoxMe — Pastebin JS

// Copy to clipboard utility
window.copyPaste = function() {
    const el = document.getElementById('paste-content');
    if (!el) return;
    const text = el.innerText;
    navigator.clipboard.writeText(text).then(() => {
        const btn = document.getElementById('copy-btn');
        if (!btn) return;
        const orig = btn.innerHTML;
        btn.textContent = '✓ Copied!';
        btn.classList.add('text-emerald-400');
        setTimeout(() => { btn.innerHTML = orig; btn.classList.remove('text-emerald-400'); }, 2000);
    }).catch(() => {
        // Fallback
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
    });
};

// Auto-dismiss flash messages
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        ['flash-success', 'flash-error'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.remove();
        });
    }, 5000);
});
