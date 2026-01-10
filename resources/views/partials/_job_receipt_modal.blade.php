@once
    @push('page-styles')
    <style>
        /* Job Receipt Modal Styles - Matching Sales Modal Style */
        #jobReceiptModal .modal-dialog {
            max-width: 800px;
        }

        #jobReceiptModal .receipt-container {
            position: relative;
            padding: 20px;
            font-family: 'Courier New', 'Consolas', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
        }

        #jobReceiptModal .modal-header {
            background: #f8f9fa;
            border-radius: 12px 12px 0 0;
            padding: 12px 16px;
        }

        #jobReceiptModal .modal-title {
            font-size: 16px;
            font-weight: 600;
            color: #495057;
        }

        #jobReceiptModal .modal-body {
            padding: 0;
        }

        /* Thermal Receipt Styles - Sales Modal Style */
        .thermal-receipt {
            width: 100%;
            max-width: 100%;
            font-family: 'Courier New', 'Consolas', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #fff;
            padding: 0;
            font-weight: 600;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .thermal-receipt * {
            font-family: 'Courier New', 'Consolas', monospace !important;
            font-weight: 600;
        }

        /* Receipt Header */
        .thermal-receipt .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .thermal-receipt .company-logo {
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

        .thermal-receipt .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .thermal-receipt .company-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 2px;
        }

        .thermal-center { text-align: center; }
        .thermal-bold { font-weight: 900; }
        .thermal-large { font-size: 14px; line-height: 1.4; font-weight: 700; }
        .thermal-small { font-size: 11px; font-weight: 600; color: #666; }
        .thermal-line { border-top: 1px dashed #ccc; margin: 10px 0; }
        .thermal-line-solid { border-top: 2px solid #333; margin: 10px 0; }
        .thermal-double-line { border-top: 3px double #333; margin: 4px 0; }
        .thermal-space { height: 12px; }
        .thermal-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 12px;
        }
        .thermal-left { text-align: left; }
        .thermal-right { text-align: right; }

        /* Job Info Section */
        .thermal-receipt .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        /* Customer Section */
        .thermal-receipt .customer-section {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        .thermal-receipt .customer-title {
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .thermal-receipt .customer-info div {
            margin-bottom: 3px;
            font-size: 11px;
        }

        /* Job Details Section */
        .thermal-receipt .job-section {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px dashed #ccc;
        }

        .thermal-receipt .job-section > div {
            margin-bottom: 5px;
            font-size: 12px;
        }

        #jobReceiptModal .print-actions {
            margin-top: 20px;
            text-align: center;
            padding: 20px 16px;
            border-top: 1px dashed #ccc;
            display: flex;
            gap: 10px;
            justify-content: center;
            background: #fff;
        }

        #jobReceiptModal .print-actions .btn {
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            cursor: pointer;
            touch-action: manipulation;
            user-select: none;
            -webkit-user-select: none;
        }

        #jobReceiptModal .print-actions .btn-primary {
            background: #3b82f6;
            color: white;
            -webkit-tap-highlight-color: rgba(59, 130, 246, 0.3);
        }

        #jobReceiptModal .print-actions .btn-primary:hover {
            background: #2563eb;
        }

        #jobReceiptModal .print-actions .btn-primary:active {
            background: #1d4ed8;
            transform: scale(0.98);
        }

        #jobReceiptModal .print-actions .btn-success {
            background: #10b981;
            color: white;
            -webkit-tap-highlight-color: rgba(16, 185, 129, 0.3);
        }

        #jobReceiptModal .print-actions .btn-success:hover {
            background: #059669;
        }

        #jobReceiptModal .print-actions .btn-success:active {
            background: #047857;
            transform: scale(0.98);
        }

        #jobReceiptModal .print-actions .btn svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        /* Print styles for thermal printer */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }

            body * {
                visibility: hidden;
            }

            .thermal-receipt,
            .thermal-receipt * {
                visibility: visible;
            }

            .thermal-receipt {
                position: absolute;
                left: 0;
                top: 0;
                width: 80mm;
                max-width: 80mm;
                padding: 2mm;
                margin: 0;
                background: #fff;
                font-weight: 700;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .thermal-receipt * {
                font-weight: 700 !important;
            }

            .thermal-bold {
                font-weight: 900 !important;
            }

            .print-actions {
                display: none !important;
            }
        }
    </style>
    @endpush
@endonce

<!-- Job Receipt Modal -->
<div class="modal fade" id="jobReceiptModal" tabindex="-1" aria-labelledby="jobReceiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 4px 20px rgba(0,0,0,0.15);">
            <div class="modal-header" style="padding:12px 16px; border-bottom:1px solid #dee2e6; background: #f8f9fa; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title" id="jobReceiptModalLabel" style="font-size: 16px; font-weight: 600; color: #495057;">Job Receipt</h5>
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
