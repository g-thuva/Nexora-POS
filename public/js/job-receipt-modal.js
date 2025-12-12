window.viewJobInModal = function(jobId) {
    // loading state
    const content = document.getElementById('job-receipt-content');
    content.innerHTML = '';
    var loadingNode = document.createElement('div');
    loadingNode.className = 'text-center p-4';
    loadingNode.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' +
        '<p class="mt-2 text-muted">Loading job details...</p>' +
        '<div id="job-receipt-status" class="text-muted small mt-2">Starting request...</div>';
    content.appendChild(loadingNode);

    // Add a timeout in case the request stalls
    var didRespond = false;
    var timeout = setTimeout(function() {
        if (!didRespond) {
            console.warn('Job receipt fetch timed out');
            var statusEl = document.getElementById('job-receipt-status'); if (statusEl) statusEl.innerText = 'Request timed out';
            showJobReceiptError('Request timed out. Please try again.');
        }
    }, 8000);

    // Debug: log URL and jobId so we can see what is requested
    try { console.log('viewJobInModal: jobId=', jobId); } catch(e){}
    var requestUrl = '/jobs/' + encodeURIComponent(jobId) + '/receipt';
    console.log('Fetching job receipt URL:', requestUrl);

    fetch(requestUrl, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async function(response) {
        didRespond = true;
        clearTimeout(timeout);
        var statusEl = document.getElementById('job-receipt-status'); if (statusEl) statusEl.innerText = 'Response received: ' + response.status;
        if (!response.ok) {
            // Try to show server response for debugging
            const txt = await response.text().catch(() => '');
            console.error('Job receipt fetch failed', response.status, txt);
            showJobReceiptError('Failed to load job details (server error)');
            return null;
        }
        // parse JSON safely — clone response so we can inspect raw text when JSON parsing fails
        try {
            const text = await response.clone().text();
            try {
                const data = JSON.parse(text);
                return data;
            } catch (e) {
                console.error('Failed to parse JSON from job receipt. Server returned:', text);
                showJobReceiptError('Invalid server response');
                return null;
            }
        } catch (e) {
            console.error('Failed to read response body for job receipt', e);
            showJobReceiptError('Invalid server response');
            return null;
        }
    })
    .then(data => {
        if (!data) return;
        if (data.success) {
            showJobReceiptModal(data.job);
        } else {
            console.error('Job receipt returned success=false', data);
            showJobReceiptError('Failed to load job details');
        }
    })
    .catch(err => {
        console.error('Network error fetching job receipt', err);
        showJobReceiptError('Network error while loading job details');
    });
}

window.showJobReceiptModal = function(jobData) {
    try {
        window.currentJobData = jobData; // store globally for download/print

        const job = jobData || {};
        const customer = job.customer || { name: 'Guest', phone: '', address: '' };
        const jobType = job.job_type || null;
        const created = job.created_at ? new Date(job.created_at).toLocaleString() : '';

        const container = document.createElement('div');

        // Header
        const header = document.createElement('div');
        header.className = 'receipt-header text-center';
        header.style.padding = '12px 10px';
        header.style.borderBottom = '2px solid #333';

        const logo = document.createElement('div');
        logo.className = 'company-logo';
        logo.textContent = 'S';
        header.appendChild(logo);

        const cname = document.createElement('div');
        cname.className = 'company-name';
        cname.textContent = 'Shop Name';
        header.appendChild(cname);

        const caddr = document.createElement('div');
        caddr.className = 'company-address';
        caddr.textContent = 'Shop Address';
        header.appendChild(caddr);

        container.appendChild(header);

        // Body
        const body = document.createElement('div');
        body.className = 'p-3';

        const info = document.createElement('div');
        info.className = 'receipt-info d-flex justify-content-between';

        const left = document.createElement('div');
        left.innerHTML = '<strong>Job Ref:</strong><br><strong>Date:</strong>';
        const right = document.createElement('div');
        right.innerHTML = (job.reference_number || '') + '<br>' + created;

        info.appendChild(left);
        info.appendChild(right);
        body.appendChild(info);

        // Customer
        const custSection = document.createElement('div');
        custSection.className = 'customer-section';
        const custTitle = document.createElement('div');
        custTitle.className = 'customer-title';
        custTitle.textContent = 'Customer Details';
        custSection.appendChild(custTitle);
        const custInfo = document.createElement('div');
        custInfo.className = 'customer-info';
        const nameDiv = document.createElement('div');
        nameDiv.innerHTML = '<strong>Name:</strong> ' + (customer.name || 'Guest');
        custInfo.appendChild(nameDiv);
        if (customer.phone) { const p = document.createElement('div'); p.innerHTML = '<strong>Phone:</strong> ' + customer.phone; custInfo.appendChild(p); }
        if (customer.address) { const a = document.createElement('div'); a.innerHTML = '<strong>Address:</strong> ' + customer.address; custInfo.appendChild(a); }
        custSection.appendChild(custInfo);
        body.appendChild(custSection);

        // Job section
        const jobSection = document.createElement('div');
        jobSection.className = 'job-section';
        const serviceType = document.createElement('div');
        serviceType.innerHTML = '<strong>Service Type:</strong> ' + (jobType && jobType.name ? jobType.name : (job.type || 'Service'));
        jobSection.appendChild(serviceType);
        const descTitle = document.createElement('div');
        descTitle.innerHTML = '<strong>Description:</strong>';
        jobSection.appendChild(descTitle);
        const desc = document.createElement('div');
        desc.style.whiteSpace = 'pre-wrap';
        desc.textContent = job.description || '-';
        jobSection.appendChild(desc);
        const est = document.createElement('div');
        est.className = 'mt-2';
        est.innerHTML = '<strong>Estimated Duration:</strong> ' + (job.estimated_duration ? (job.estimated_duration + ' days') : 'Not provided');
        jobSection.appendChild(est);
        const status = document.createElement('div');
        status.innerHTML = '<strong>Status:</strong> ' + (job.status ? job.status.replace('_',' ') : '');
        jobSection.appendChild(status);
        body.appendChild(jobSection);

        // Action buttons
        const actions = document.createElement('div');
        actions.className = 'print-actions';
        actions.style.display = 'flex';
        actions.style.gap = '10px';
        actions.style.justifyContent = 'center';

        // POS Print button
        const printBtn = document.createElement('button');
        printBtn.className = 'btn btn-primary';
        printBtn.type = 'button';
        printBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path><rect x="7" y="13" width="10" height="8" rx="2"></rect></svg> POS Print`;
        printBtn.addEventListener('click', function() { window.printJobReceipt(); });
        actions.appendChild(printBtn);

        // Download PDF button
        const pdfBtn = document.createElement('a');
        pdfBtn.className = 'btn btn-success';
        pdfBtn.href = '/jobs/' + job.id + '/pdf-job-sheet';
        pdfBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path><path d="M7 11l5 5l5 -5"></path><path d="M12 4l0 12"></path></svg> Download PDF`;
        actions.appendChild(pdfBtn);

        body.appendChild(actions);

        container.appendChild(body);

        const target = document.getElementById('job-receipt-content');
        target.innerHTML = '';
        target.appendChild(container);
    } catch (err) {
        console.error('Error rendering job receipt modal', err);
        window.showJobReceiptError('Failed to render receipt');
    }
}

window.showJobReceiptError = function(message) {
    var el = document.getElementById('job-receipt-content');
    if (!el) return;
    el.innerHTML = '';
    var wrap = document.createElement('div');
    wrap.className = 'text-center p-4';
    var icon = document.createElement('div');
    icon.className = 'text-danger mb-3';
    icon.textContent = '⚠️';
    wrap.appendChild(icon);
    var p = document.createElement('p');
    p.className = 'text-muted';
    p.textContent = message || 'An error occurred';
    wrap.appendChild(p);
    el.appendChild(wrap);
}

window.closeJobReceiptModal = function() {
    try {
        const modalElement = document.getElementById('jobReceiptModal');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) modal.hide();
            else new bootstrap.Modal(modalElement).hide();
        } else if (typeof $ !== 'undefined' && $.fn.modal) {
            $(modalElement).modal('hide');
        } else {
            modalElement.style.display = 'none';
            modalElement.classList.remove('show');
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop'); if (backdrop) backdrop.remove();
        }
        setTimeout(function() {
            const el = document.getElementById('job-receipt-content');
            if (el) {
                el.innerHTML = '';
                var ln = document.createElement('div');
                ln.className = 'text-center p-4';
                ln.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' +
                    '<p class="mt-2 text-muted">Loading job details...</p>';
                el.appendChild(ln);
            }
        }, 300);
    } catch (e) {
        console.error('Error closing modal', e);
    }
}

window.printJobReceipt = function() {
    try {
        var receiptContentHtml = document.getElementById('job-receipt-content').innerHTML || '<div>No receipt content</div>';
        console.debug('printJobReceipt: content length=', receiptContentHtml.length);
        // Prefer iframe-based printing using the reusable helper
        if (window.printHelper && typeof window.printHelper.printHtmlViaIframe === 'function') {
            window.printHelper.printHtmlViaIframe(receiptContentHtml, { title: 'Job Receipt' })
                .then(function(res){
                    // printed or at least attempted
                    console.log('printHelper result', res);
                })
                .catch(function(err){
                    console.warn('printHelper failed, falling back to popup', err);
                    fallbackPopupPrint(receiptContentHtml);
                });
            return;
        }
    } catch (e) {
        console.error('printJobReceipt helper attempt failed', e);
    }

    // last resort fallback
    var receiptContentFallback = document.getElementById('job-receipt-content').innerHTML || '<div>No receipt content</div>';
    fallbackPopupPrint(receiptContentFallback);
}

function fallbackPopupPrint(htmlContent) {
    var printWindow = window.open('', '_blank', 'width=600,height=800');
    if (!printWindow) {
        alert('Popup blocked. Please allow popups for this site to enable printing.');
        return;
    }
    var inlineStyles = '<style>' +
        "body { font-family: 'Courier New', monospace; color: #333; padding: 10px; }" +
        '.company-logo { width:50px; height:50px; background:#3b82f6; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:24px; margin:0 auto 6px; }' +
        '.company-name { font-weight:700; text-align:center; }' +
        '.company-address { text-align:center; font-size:11px; color:#666; }' +
        '.receipt-info { margin-bottom:12px; }' +
        '</style>';
    printWindow.document.open();
    printWindow.document.write('<!doctype html><html><head><title>Job Receipt</title>' + inlineStyles + '</head><body>' + htmlContent + '</body></html>');
    printWindow.document.close();
    setTimeout(function() {
        try { printWindow.focus(); printWindow.print(); } catch (e) { console.error('Print failed', e); }
    }, 300);
}

// optional: clean up when modal hides
document.addEventListener('DOMContentLoaded', function() {
    const modalEl = document.getElementById('jobReceiptModal');
    if (modalEl) {
        modalEl.addEventListener('hidden.bs.modal', function () {
            window.currentJobData = null;
            const el = document.getElementById('job-receipt-content');
            if (el) {
                el.innerHTML = '';
                var ln = document.createElement('div');
                ln.className = 'text-center p-4';
                ln.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' +
                    '<p class="mt-2 text-muted">Loading job details...</p>';
                el.appendChild(ln);
            }
        });

        // bind close button (unobtrusive)
        var closeBtn = document.getElementById('jobReceiptModalClose');
        if (closeBtn) {
            closeBtn.addEventListener('click', function () { window.closeJobReceiptModal(); });
        }
    }

    // bind all 'open receipt' buttons
    var openBtns = document.querySelectorAll('.js-open-job-receipt');
    if (openBtns && openBtns.length) {
        openBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                var id = btn.getAttribute('data-job-id') || btn.dataset.jobId;
                if (id) {
                    try { window.viewJobInModal(id); } catch (err) { console.error('viewJobInModal error', err); }
                }
            });
        });
    }
});
