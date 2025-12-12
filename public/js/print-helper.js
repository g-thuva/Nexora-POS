// Reusable print helper: print HTML using a hidden iframe to avoid modal/backdrop issues
(function(window){
    function createPrintIframe() {
        var iframe = document.createElement('iframe');
        iframe.style.position = 'fixed';
        iframe.style.right = '0';
        iframe.style.bottom = '0';
        iframe.style.width = '0';
        iframe.style.height = '0';
        iframe.style.border = '0';
        iframe.style.overflow = 'hidden';
        iframe.setAttribute('aria-hidden','true');
        iframe.id = 'print-helper-iframe-' + Date.now();
        document.body.appendChild(iframe);
        return iframe;
    }

    function printHtmlViaIframe(html, options) {
        options = options || {};
        return new Promise(function(resolve, reject){
            console.debug('printHelper.printHtmlViaIframe called; html length=', (html || '').length, 'options=', options);
            try {
                var iframe = createPrintIframe();
                console.debug('printHelper: created iframe id=', iframe.id);
                var doc = iframe.contentWindow || iframe.contentDocument;
                if (iframe.contentDocument) doc = iframe.contentDocument;
                var title = options.title || 'Print';
                var style = options.style || '';

                var content = '<!doctype html><html><head><meta charset="utf-8"><title>' + escapeHtml(title) + '</title>';
                content += '<meta name="viewport" content="width=device-width,initial-scale=1">';
                content += '<style>body{margin:0;padding:8px;font-family:"Courier New",monospace;color:#333}' + style + '</style>';
                content += '</head><body>' + html + '</body></html>';

                // Write and wait for load
                doc.open();
                doc.write(content);
                doc.close();

                console.debug('printHelper: written content to iframe, waiting before print');

                // Some browsers need a short delay
                setTimeout(function(){
                    try {
                        // Focus the iframe window if possible
                        if (iframe.contentWindow) iframe.contentWindow.focus();
                        // Use print and resolve after a short delay
                        var printed = false;
                        try { iframe.contentWindow.print(); printed = true; console.debug('printHelper: iframe.print() called'); } catch(e){ console.warn('iframe print threw', e); }
                        // Cleanup after print
                        setTimeout(function(){
                            try { iframe.remove(); } catch(e) { if (iframe.parentNode) iframe.parentNode.removeChild(iframe); }
                            resolve({ printed: printed });
                        }, 500);
                    } catch (err) {
                        try { iframe.remove(); } catch(e){}
                        reject(err);
                    }
                }, 250);
            } catch (err) {
                console.error('printHelper: unexpected error in printHtmlViaIframe', err);
                reject(err);
            }
        });
    }

    function escapeHtml(s) {
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    // Expose
    window.printHelper = window.printHelper || {};
    window.printHelper.printHtmlViaIframe = printHtmlViaIframe;
})(window);
