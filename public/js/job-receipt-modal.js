// Direct POS Print without modal
window.posPrintJobReceipt = function(jobId) {
    console.log('posPrintJobReceipt called with jobId:', jobId);

    var requestUrl = '/jobs/' + encodeURIComponent(jobId) + '/receipt';
    console.log('Fetching job receipt for POS print:', requestUrl);

    fetch(requestUrl, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(async function(response) {
        if (!response.ok) {
            const txt = await response.text().catch(() => '');
            console.error('Job receipt fetch failed', response.status, txt);
            alert('Failed to load job details');
            return null;
        }
        try {
            const text = await response.clone().text();
            const data = JSON.parse(text);
            return data;
        } catch (e) {
            console.error('Failed to parse JSON', e);
            alert('Invalid server response');
            return null;
        }
    })
    .then(data => {
        if (!data || !data.success) {
            console.error('No valid data returned');
            return;
        }
        console.log('POS printing job data:', data.job);
        window.printThermalReceipt(data.job);
    })
    .catch(err => {
        console.error('Network error', err);
        alert('Network error while loading job details');
    });
}

// Thermal receipt printing (80mm)
window.printThermalReceipt = function(jobData) {
    console.log('printThermalReceipt called');

    const job = jobData || {};
    const customer = job.customer || { name: 'Walk-in Customer', phone: '', address: '' };
    const shop = job.shop || { name: 'NexoraLabs', address: '', phone: '', email: '' };
    const created = job.created_at ? new Date(job.created_at).toLocaleString('en-US', {
        year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
    }) : '';

    // Build thermal receipt HTML (80mm width)
    let html = '<div style="width: 80mm; font-family: monospace; font-size: 12px; font-weight: 700; padding: 5mm;">';

    // Shop header
    html += '<div style="text-align: center; margin-bottom: 10px; border-bottom: 2px dashed #000; padding-bottom: 10px;">';
    html += '<div style="font-size: 18px; font-weight: 900; margin-bottom: 5px;">' + (shop.name || 'NexoraLabs') + '</div>';
    if (shop.address) html += '<div style="font-size: 11px; font-weight: 600;">' + shop.address + '</div>';
    if (shop.phone) html += '<div style="font-size: 11px; font-weight: 600;">Phone: ' + shop.phone + '</div>';
    if (shop.email) html += '<div style="font-size: 11px; font-weight: 600;">Email: ' + shop.email + '</div>';
    html += '</div>';

    // Receipt info
    html += '<div style="margin: 10px 0; border-bottom: 1px dashed #000; padding-bottom: 10px;">';
    html += '<div style="font-weight: 900; font-size: 14px; margin-bottom: 5px;">JOB RECEIPT</div>';
    html += '<div style="display: flex; justify-content: space-between; font-weight: 700;">';
    html += '<span>Ref:</span><span>' + (job.reference_number || 'N/A') + '</span>';
    html += '</div>';
    html += '<div style="display: flex; justify-content: space-between; font-weight: 700;">';
    html += '<span>Date:</span><span>' + created + '</span>';
    html += '</div>';
    html += '<div style="display: flex; justify-content: space-between; font-weight: 700;">';
    html += '<span>Status:</span><span>' + (job.status ? job.status.replace('_', ' ').toUpperCase() : 'N/A') + '</span>';
    html += '</div>';
    html += '</div>';

    // Customer info
    html += '<div style="margin: 10px 0; border-bottom: 1px dashed #000; padding-bottom: 10px;">';
    html += '<div style="font-weight: 900; font-size: 13px; margin-bottom: 5px;">CUSTOMER</div>';
    html += '<div style="font-weight: 700;">' + customer.name + '</div>';
    if (customer.phone) html += '<div style="font-weight: 700;">Phone: ' + customer.phone + '</div>';
    if (customer.address) html += '<div style="font-weight: 700;">Address: ' + customer.address + '</div>';
    html += '</div>';

    // Job details
    html += '<div style="margin: 10px 0; border-bottom: 1px dashed #000; padding-bottom: 10px;">';
    html += '<div style="font-weight: 900; font-size: 13px; margin-bottom: 5px;">JOB DETAILS</div>';
    html += '<div style="display: flex; justify-content: space-between; font-weight: 700;">';
    html += '<span>Type:</span><span>' + (job.type || 'N/A') + '</span>';
    html += '</div>';
    if (job.job_type && job.job_type.name) {
        html += '<div style="display: flex; justify-content: space-between; font-weight: 700;">';
        html += '<span>Service:</span><span>' + job.job_type.name + '</span>';
        html += '</div>';
    }
    if (job.estimated_duration) {
        html += '<div style="display: flex; justify-content: space-between; font-weight: 700;">';
        html += '<span>Duration:</span><span>' + job.estimated_duration + '</span>';
        html += '</div>';
    }
    if (job.description) {
        html += '<div style="margin-top: 8px; font-weight: 700;">';
        html += '<div style="font-weight: 900;">Description:</div>';
        html += '<div>' + job.description + '</div>';
        html += '</div>';
    }
    html += '</div>';

    // Footer
    html += '<div style="text-align: center; margin-top: 15px; font-size: 11px; font-weight: 700;">';
    html += '<div>Thank you for your business!</div>';
    html += '</div>';

    html += '</div>';

    // Print styles for thermal printer
    const thermalStyles = `
        @page { size: 80mm auto; margin: 0; }
        @media print {
            body { margin: 0; padding: 0; width: 80mm; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    `;

    // Try using print helper first
    if (typeof printHelper !== 'undefined' && printHelper && printHelper.print) {
        console.log('Using printHelper for thermal receipt');
        printHelper.print(html, thermalStyles);
    } else {
        console.log('Using fallback thermal print');
        window.fallbackThermalPrint(html, thermalStyles);
    }
}

// Fallback thermal print using popup
window.fallbackThermalPrint = function(htmlContent, customStyles) {
    console.log('fallbackThermalPrint called');
    const printWindow = window.open('', '', 'width=400,height=600');
    if (!printWindow) {
        alert('Please allow popups for printing');
        return;
    }

    printWindow.document.write('<html><head><title>Print Receipt</title>');
    printWindow.document.write('<style>' + customStyles + '</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(htmlContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 300);
}

window.viewJobInModal = function(jobId) {
    console.log('viewJobInModal called with jobId:', jobId);

    // Open the modal first
    const modalElement = document.getElementById('jobReceiptModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }

    // loading state
    const content = document.getElementById('job-receipt-content');
    if (!content) {
        console.error('job-receipt-content element not found');
        return;
    }

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
            window.showJobReceiptError('Request timed out. Please try again.');
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
            window.showJobReceiptError('Failed to load job details (server error)');
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
                window.showJobReceiptError('Invalid server response');
                return null;
            }
        } catch (e) {
            console.error('Failed to read response body for job receipt', e);
            window.showJobReceiptError('Invalid server response');
            return null;
        }
    })
    .then(data => {
        console.log('Job receipt data received:', data);
        if (!data) {
            console.log('No data returned');
            return;
        }
        if (data.success) {
            console.log('Calling showJobReceiptModal with:', data.job);
            window.showJobReceiptModal(data.job);
        } else {
            console.error('Job receipt returned success=false', data);
            window.showJobReceiptError('Failed to load job details');
        }
    })
    .catch(err => {
        console.error('Network error fetching job receipt', err);
        window.showJobReceiptError('Network error while loading job details');
    });
}

window.showJobReceiptModal = function(jobData) {
    console.log('showJobReceiptModal called with:', jobData);
    try {
        window.currentJobData = jobData;

        const job = jobData || {};
        console.log('Job data:', job);
        const customer = job.customer || { name: 'Walk-in Customer', phone: '', address: '' };
        const jobType = job.job_type || null;
        const shop = job.shop || { name: 'NexoraLabs', address: '', phone: '', email: '' };
        const created = job.created_at ? new Date(job.created_at).toLocaleString('en-US', {
            year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
        }) : '';

        // Create receipt container with sales modal style
        const container = document.createElement('div');
        container.className = 'thermal-receipt';

        // Header - Shop Info (like sales receipt)
        const header = document.createElement('div');
        header.className = 'receipt-header';

        const logo = document.createElement('div');
        logo.className = 'company-logo';
        logo.textContent = (shop.name || 'NexoraLabs').charAt(0).toUpperCase();
        header.appendChild(logo);

        const companyName = document.createElement('div');
        companyName.className = 'company-name';
        companyName.textContent = shop.name || 'NexoraLabs';
        header.appendChild(companyName);

        if (shop.address) {
            const addr = document.createElement('div');
            addr.className = 'company-address';
            addr.textContent = shop.address;
            header.appendChild(addr);
        }

        if (shop.phone) {
            const phone = document.createElement('div');
            phone.className = 'company-address';
            phone.textContent = 'Phone: ' + shop.phone;
            header.appendChild(phone);
        }

        if (shop.email) {
            const email = document.createElement('div');
            email.className = 'company-address';
            email.textContent = 'Email: ' + shop.email;
            header.appendChild(email);
        }

        container.appendChild(header);

        // Receipt Info - Job Reference and Date
        const receiptInfo = document.createElement('div');
        receiptInfo.className = 'receipt-info';

        const leftInfo = document.createElement('div');
        leftInfo.innerHTML = '<strong>Job Ref:</strong><br>' + (job.reference_number || 'N/A') + '<br><strong>Status:</strong> ' + ((job.status || 'pending').replace('_', ' ').toUpperCase());

        const rightInfo = document.createElement('div');
        rightInfo.style.textAlign = 'right';
        rightInfo.innerHTML = '<strong>Date:</strong><br>' + created;

        receiptInfo.appendChild(leftInfo);
        receiptInfo.appendChild(rightInfo);
        container.appendChild(receiptInfo);

        // Customer Section
        const customerSection = document.createElement('div');
        customerSection.className = 'customer-section';

        const customerTitle = document.createElement('div');
        customerTitle.className = 'customer-title';
        customerTitle.textContent = 'Customer Information';
        customerSection.appendChild(customerTitle);

        const customerInfo = document.createElement('div');
        customerInfo.className = 'customer-info';

        const custName = document.createElement('div');
        custName.innerHTML = '<strong>Name:</strong> ' + (customer.name || 'Walk-in');
        customerInfo.appendChild(custName);

        if (customer.phone) {
            const custPhone = document.createElement('div');
            custPhone.innerHTML = '<strong>Phone:</strong> ' + customer.phone;
            customerInfo.appendChild(custPhone);
        }

        if (customer.email) {
            const custEmail = document.createElement('div');
            custEmail.innerHTML = '<strong>Email:</strong> ' + customer.email;
            customerInfo.appendChild(custEmail);
        }

        if (customer.address) {
            const custAddr = document.createElement('div');
            custAddr.innerHTML = '<strong>Address:</strong> ' + customer.address;
            customerInfo.appendChild(custAddr);
        }

        customerSection.appendChild(customerInfo);
        container.appendChild(customerSection);

        // Job Details Section
        const jobSection = document.createElement('div');
        jobSection.className = 'job-section';

        const jobTitle = document.createElement('div');
        jobTitle.innerHTML = '<strong>Job Type:</strong> ' + (jobType && jobType.name ? jobType.name : (job.type || 'Service'));
        jobSection.appendChild(jobTitle);

        if (job.estimated_duration) {
            const duration = document.createElement('div');
            duration.innerHTML = '<strong>Estimated Duration:</strong> ' + job.estimated_duration + ' days';
            jobSection.appendChild(duration);
        }

        if (job.description) {
            const descTitle = document.createElement('div');
            descTitle.innerHTML = '<strong>Description:</strong>';
            descTitle.style.marginTop = '10px';
            jobSection.appendChild(descTitle);

            const desc = document.createElement('div');
            desc.textContent = job.description;
            desc.style.whiteSpace = 'pre-wrap';
            desc.style.marginTop = '5px';
            desc.style.color = '#666';
            jobSection.appendChild(desc);
        }

        container.appendChild(jobSection);

        // Action buttons (sales modal style)
        const actions = document.createElement('div');
        actions.className = 'print-actions';

        const printBtn = document.createElement('button');
        printBtn.className = 'btn btn-primary';
        printBtn.type = 'button';
        printBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2"></path><path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4"></path><rect x="7" y="13" width="10" height="8" rx="2"></rect></svg> POS Print';
        printBtn.addEventListener('click', () => window.printJobReceipt());
        actions.appendChild(printBtn);

        const pdfBtn = document.createElement('button');
        pdfBtn.className = 'btn btn-success';
        pdfBtn.type = 'button';
        pdfBtn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path><path d="M7 11l5 5l5 -5"></path><path d="M12 4l0 12"></path></svg> Download PDF';
        pdfBtn.addEventListener('click', () => {
            // Create a temporary link element for download
            const link = document.createElement('a');
            link.href = '/jobs/' + job.id + '/pdf-job-sheet';
            link.download = 'JobCard_' + (job.reference_number || job.id) + '.pdf';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
        actions.appendChild(pdfBtn);

        const target = document.getElementById('job-receipt-content');
        target.innerHTML = '';
        target.appendChild(container);
        target.appendChild(actions);
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
    console.log('printJobReceipt called');
    try {
        var receiptElement = document.querySelector('.thermal-receipt');
        if (!receiptElement) {
            console.error('No thermal receipt element found');
            receiptElement = document.getElementById('job-receipt-content');
        }

        if (!receiptElement) {
            console.error('No receipt content element found at all');
            alert('Unable to print - receipt content not found');
            return;
        }

        var receiptContentHtml = receiptElement.outerHTML;
        console.log('printJobReceipt: content length=', receiptContentHtml.length);

        // Custom styles for printing (matching the modal display)
        var printStyles = `
            @page { size: A4 portrait; margin: 15mm; }
            @media print {
                body { margin: 0; padding: 0; background: #fff; }
                * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }

                .thermal-receipt {
                    width: 100%;
                    max-width: 100%;
                    font-family: 'Courier New', 'Consolas', monospace;
                    font-size: 12px;
                    line-height: 1.4;
                    color: #333;
                }

                .receipt-header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 15px;
                }

                .company-logo {
                    width: 50px;
                    height: 50px;
                    background: #3b82f6;
                    color: white;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    font-weight: bold;
                    margin: 0 auto 10px;
                }

                .company-name {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 5px;
                }

                .company-address {
                    font-size: 11px;
                    color: #666;
                    margin-bottom: 2px;
                }

                .receipt-info {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 1px dashed #ccc;
                }

                .customer-section {
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 1px dashed #ccc;
                }

                .customer-title {
                    font-weight: bold;
                    margin-bottom: 8px;
                    font-size: 13px;
                }

                .customer-info div {
                    margin-bottom: 3px;
                    font-size: 11px;
                }

                .job-section {
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 1px dashed #ccc;
                }

                .job-section > div {
                    margin-bottom: 5px;
                    font-size: 12px;
                }

                .print-actions { display: none !important; }
            }
        `;

        // Use iframe-based printing
        if (window.printHelper && typeof window.printHelper.printHtmlViaIframe === 'function') {
            console.log('Using printHelper.printHtmlViaIframe');
            window.printHelper.printHtmlViaIframe(receiptContentHtml, {
                title: 'Job Receipt',
                style: printStyles
            })
            .then(function(res){
                console.log('printHelper result', res);
            })
            .catch(function(err){
                console.warn('printHelper failed, falling back to popup', err);
                fallbackPopupPrint(receiptContentHtml, printStyles);
            });
            return;
        } else {
            console.log('printHelper not available, using fallback');
            fallbackPopupPrint(receiptContentHtml, printStyles);
        }
    } catch (e) {
        console.error('printJobReceipt error:', e);
        alert('Error printing receipt: ' + e.message);
    }
}

function fallbackPopupPrint(htmlContent, customStyles) {
    console.log('fallbackPopupPrint called');
    var printWindow = window.open('', '_blank', 'width=800,height=600');
    if (!printWindow) {
        alert('Popup blocked. Please allow popups for this site to enable printing.');
        return;
    }

    var defaultStyles = '<style>' +
        '@page { size: A4 portrait; margin: 15mm; }' +
        "body { margin: 0; padding: 0; font-family: 'Courier New', 'Consolas', monospace; background: #fff; }" +
        (customStyles || '') +
        '</style>';

    printWindow.document.open();
    printWindow.document.write('<!doctype html><html><head><meta charset="utf-8"><title>Job Receipt</title>' + defaultStyles + '</head><body>' + htmlContent + '</body></html>');
    printWindow.document.close();
    setTimeout(function() {
        try {
            printWindow.focus();
            printWindow.print();
        } catch (e) {
            console.error('Print failed', e);
        }
    }, 500);
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

        // ESC key to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal && modalEl.classList.contains('show')) {
                    window.closeJobReceiptModal();
                }
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
