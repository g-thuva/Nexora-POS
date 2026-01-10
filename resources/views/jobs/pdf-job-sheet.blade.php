<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Job Card - {{ $job->reference_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 10mm 15mm;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #2d3748;
            background: #ffffff;
        }

        .page {
            width: 100%;
            background: white;
            position: relative;
        }

        /* Modern Header with Accent Bar */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            margin-bottom: 15px;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .shop-info {
            display: table-cell;
            width: 65%;
            vertical-align: middle;
        }

        .shop-name {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .shop-details {
            font-size: 9px;
            opacity: 0.95;
            line-height: 1.5;
        }

        .shop-details div {
            margin: 2px 0;
        }

        .job-card-badge {
            display: table-cell;
            width: 35%;
            text-align: right;
            vertical-align: middle;
        }

        .badge-content {
            display: inline-block;
            background: white;
            color: #667eea;
            padding: 10px 16px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .badge-label {
            font-size: 8px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-title {
            font-size: 16px;
            font-weight: 700;
            color: #667eea;
            margin-top: 2px;
        }

        /* Job Reference Box */
        .job-ref-container {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 18px 25px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.25);
        }

        .job-ref-grid {
            display: table;
            width: 100%;
        }

        .job-ref-main {
            display: table-cell;
            width: 60%;
            vertical-align: middle;
        }

        .job-ref-label {
            font-size: 10px;
            opacity: 0.9;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .job-ref-number {
            font-size: 24px;
            font-weight: 700;
            margin-top: 4px;
            letter-spacing: 2px;
        }

        .job-ref-meta {
            display: table-cell;
            width: 40%;
            text-align: right;
            vertical-align: middle;
            font-size: 9px;
            opacity: 0.95;
        }

        .job-ref-meta div {
            margin: 3px 0;
        }

        /* Section Styling */
        .section {
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            page-break-inside: avoid;
        }

        .section-header {
            background: linear-gradient(to right, #f7fafc 0%, #edf2f7 100%);
            padding: 10px 18px;
            border-bottom: 2px solid #cbd5e0;
        }

        .section-title {
            font-size: 11px;
            font-weight: 700;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            display: inline-flex;
            align-items: center;
        }

        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 12px;
            background: #667eea;
            margin-right: 8px;
            border-radius: 2px;
        }

        .section-content {
            padding: 15px 18px;
            background: white;
        }

        /* Info Grid - Clean Table Layout */
        .info-grid {
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .info-row {
            display: table-row;
        }

        .info-row:not(:last-child) .info-label,
        .info-row:not(:last-child) .info-value {
            border-bottom: 1px solid #e2e8f0;
        }

        .info-label {
            display: table-cell;
            padding: 10px 15px;
            font-weight: 600;
            color: #4a5568;
            width: 38%;
            background: #f7fafc;
            font-size: 10px;
        }

        .info-value {
            display: table-cell;
            padding: 10px 15px;
            color: #2d3748;
            font-size: 10px;
        }

        /* Status Badges - Modern Design */
        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }

        .status-in_progress {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #3b82f6;
        }

        .status-completed {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .status-on_hold {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #ef4444;
        }

        .status-cancelled {
            background: #e5e7eb;
            color: #374151;
            border: 1px solid #9ca3af;
        }

        /* Description Box */
        .description-box {
            background: #f8fafc;
            border-left: 4px solid #667eea;
            padding: 12px 15px;
            border-radius: 4px;
            margin-top: 10px;
            line-height: 1.5;
        }

        .description-label {
            font-weight: 700;
            color: #667eea;
            font-size: 10px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .description-text {
            color: #4a5568;
            font-size: 10px;
            line-height: 1.6;
        }

        /* Work Notes Section */
        .work-notes {
            min-height: 80px;
            border: 2px dashed #cbd5e0;
            border-radius: 6px;
            padding: 12px;
            background: #fafafa;
        }

        .work-notes-label {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notes-lines {
            display: block;
        }

        .notes-line {
            height: 16px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 6px;
        }

        /* Signature Section - Side by Side */
        .signature-section {
            margin-top: 20px;
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0;
            page-break-inside: avoid;
        }

        .signature-box {
            display: table-cell;
            width: 48%;
            padding: 15px;
            border: 2px solid #cbd5e0;
            border-radius: 8px;
            vertical-align: top;
            background: #fafafa;
        }

        .signature-title {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-align: center;
            padding-bottom: 6px;
            border-bottom: 2px solid #3182ce;
        }

        .signature-line {
            border-bottom: 2px solid #2d3748;
            height: 35px;
            margin: 12px 0;
        }

        .signature-field {
            font-size: 9px;
            color: #718096;
            margin: 6px 0;
            font-weight: 500;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 2px solid #e2e8f0;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 50%;
            vertical-align: middle;
            font-size: 8px;
            color: #718096;
        }

        .footer-right {
            display: table-cell;
            width: 50%;
            text-align: right;
            vertical-align: middle;
            font-size: 8px;
            color: #718096;
        }

        .page-number {
            font-weight: 600;
            color: #4a5568;
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(203, 213, 224, 0.15);
            font-weight: 700;
            z-index: 0;
            pointer-events: none;
            text-transform: uppercase;
            letter-spacing: 10px;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="watermark">JOB CARD</div>

        <div class="content-wrapper">
            {{-- Modern Header --}}
            <div class="header">
                <div class="header-content">
                    <div class="shop-info">
                        <div class="shop-name">{{ $shop->name ?? 'Your Shop Name' }}</div>
                        <div class="shop-details">
                            @if($shop->address)
                                <div>{{ $shop->address }}</div>
                            @endif
                            @if($shop->phone)
                                <div>Phone: {{ $shop->phone }}</div>
                            @endif
                            @if($shop->email)
                                <div>Email: {{ $shop->email }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="job-card-badge">
                        <div class="badge-content">
                            <div class="badge-label">Document Type</div>
                            <div class="badge-title">JOB CARD</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Job Reference Container --}}
            <div class="job-ref-container">
                <div class="job-ref-grid">
                    <div class="job-ref-main">
                        <div class="job-ref-label">Job Reference Number</div>
                        <div class="job-ref-number">{{ $job->reference_number }}</div>
                    </div>
                    <div class="job-ref-meta">
                        <div><strong>Created:</strong> {{ \Carbon\Carbon::parse($job->created_at)->format('d M Y') }}</div>
                        <div><strong>Time:</strong> {{ \Carbon\Carbon::parse($job->created_at)->format('h:i A') }}</div>
                        <div><strong>Status:</strong> {{ str_replace('_', ' ', ucfirst($job->status)) }}</div>
                    </div>
                </div>
            </div>

            {{-- Customer Information --}}
            @if($job->customer)
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Customer Information</div>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Customer Name</div>
                            <div class="info-value">{{ $job->customer->name }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Contact Number</div>
                            <div class="info-value">{{ $job->customer->phone ?? 'Not provided' }}</div>
                        </div>
                        @if($job->customer->email)
                        <div class="info-row">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $job->customer->email }}</div>
                        </div>
                        @endif
                        @if($job->customer->address)
                        <div class="info-row">
                            <div class="info-label">Address</div>
                            <div class="info-value">{{ $job->customer->address }}</div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            {{-- Job Details --}}
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Job Details & Specifications</div>
                </div>
                <div class="section-content">
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Job Type / Category</div>
                            <div class="info-value">{{ $job->jobType->name ?? $job->type ?? 'General Service' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Current Status</div>
                            <div class="info-value">
                                <span class="status-badge status-{{ $job->status }}">
                                    {{ str_replace('_', ' ', ucfirst($job->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Estimated Duration</div>
                            <div class="info-value">{{ $job->estimated_duration ? $job->estimated_duration . ' days' : 'To be determined' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date Received</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($job->created_at)->format('d F Y, h:i A') }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($job->updated_at)->format('d F Y, h:i A') }}</div>
                        </div>
                    </div>

                    @if($job->description)
                    <div class="description-box">
                        <div class="description-label">Job Description & Requirements</div>
                        <div class="description-text">{{ $job->description }}</div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Work Performed Section --}}
            <div class="section">
                <div class="section-header">
                    <div class="section-title">Work Performed & Technical Notes</div>
                </div>
                <div class="section-content">
                    <div class="work-notes">
                        <div class="work-notes-label">Technician's Work Summary & Observations:</div>
                        <div class="notes-lines">
                            <div class="notes-line"></div>
                            <div class="notes-line"></div>
                            <div class="notes-line"></div>
                            <div class="notes-line"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Signature Section --}}
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-title">Technician / Service Provider</div>
                    <div class="signature-line"></div>
                    <div class="signature-field">Name: _________________________________</div>
                    <div class="signature-field">Employee ID: ___________________________</div>
                    <div class="signature-field">Date: __________________________________</div>
                </div>
                <div class="signature-box">
                    <div class="signature-title">Customer Acknowledgment</div>
                    <div class="signature-line"></div>
                    <div class="signature-field">Name: _________________________________</div>
                    <div class="signature-field">Signature: _____________________________</div>
                    <div class="signature-field">Date: __________________________________</div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="footer">
                <div class="footer-content">
                    <div class="footer-left">
                        <div>This is a computer-generated document. No signature required.</div>
                        <div style="margin-top: 3px;">For inquiries, please contact: {{ $shop->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="footer-right">
                        <div><span class="page-number">Page 1 of 1</span></div>
                        <div style="margin-top: 3px;">Generated: {{ now()->format('d M Y \a\t h:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
