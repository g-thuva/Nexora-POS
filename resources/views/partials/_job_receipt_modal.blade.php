@once
    @push('page-styles')
    <style>
        /* Job Receipt Modal Styles */
        #jobReceiptModal .receipt-container {
            position: relative;
            padding: 18px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        #jobReceiptModal .modal-header {
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
        }

        #jobReceiptModal .company-logo {
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
            margin: 0 auto 8px;
        }

        #jobReceiptModal .company-name { font-size: 16px; font-weight: bold; }
        #jobReceiptModal .company-address { font-size: 11px; color: #666; }

        #jobReceiptModal .receipt-info { margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px dashed #ccc; }
        #jobReceiptModal .customer-section { margin-bottom: 10px; }
        #jobReceiptModal .customer-title { font-weight: bold; margin-bottom: 6px; font-size: 13px; }

        #jobReceiptModal .print-actions {
            margin-top: 14px;
            text-align: center;
            padding: 16px;
            border-top: 1px dashed #ccc;
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        #jobReceiptModal .print-actions .btn {
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        #jobReceiptModal .print-actions .btn svg {
            width: 18px;
            height: 18px;
        }

        /* Print styles for modal - only print the wrapper content */
        @media print {
            body * { display: none !important; visibility: hidden !important; }
            #print-job-wrapper, #print-job-wrapper * { display: block !important; visibility: visible !important; }
            #print-job-wrapper {
                position: fixed !important;
                left: 0 !important; top: 0 !important;
                width: 80mm !important; min-width: 80mm !important; max-width: 100vw !important;
                background: #fff !important; padding: 8px !important; margin: 0 auto !important; z-index: 9999 !important;
                font-family: 'Courier New', monospace !important;
            }
        }
    </style>
    @endpush
@endonce

<!-- Job Receipt Modal -->
<div class="modal fade" id="jobReceiptModal" tabindex="-1" aria-labelledby="jobReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-header" style="padding:12px 16px; border-bottom:1px solid #dee2e6;">
                    <h5 class="modal-title" id="jobReceiptModalLabel">Job Receipt</h5>
                    <button type="button" id="jobReceiptModalClose" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="receipt-container" id="job-receipt-content">
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                        <p class="mt-2 text-muted">Loading job details...</p>
                    </div>
                </div>
                <div id="print-job-wrapper" style="display:none;"></div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
    <?php $ver1 = file_exists(public_path('js/print-helper.js')) ? filemtime(public_path('js/print-helper.js')) : time(); ?>
    <?php $ver2 = file_exists(public_path('js/job-receipt-modal.js')) ? filemtime(public_path('js/job-receipt-modal.js')) : time(); ?>
    <script src="<?php echo e(asset('js/print-helper.js')); ?>?v=<?php echo e($ver1); ?>"></script>
    <script src="<?php echo e(asset('js/job-receipt-modal.js')); ?>?v=<?php echo e($ver2); ?>"></script>
@endpush
