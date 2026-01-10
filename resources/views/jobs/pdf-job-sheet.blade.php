<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Card - {{ $job->reference_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: A4 portrait;
            margin: 15mm 20mm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            line-height: 1.5;
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
            background: #1a202c;
            color: white;
            padding: 25px 30px;
            margin-bottom: 20px;
            position: relative;
            border-bottom: 4px solid #3182ce;
        }

        .header::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 120px;
            height: 4px;
            background: #48bb78;
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
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .shop-details {
            font-size: 10px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .shop-details div {
            margin: 3px 0;
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
            color: #1a202c;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .badge-label {
            font-size: 9px;
            font-weight: 600;
            color: #718096;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-title {
            font-size: 18px;
            font-weight: 700;
            color: #3182ce;
            margin-top: 2px;
        }

        /* Job Reference Box */
        .job-ref-container {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px 30px;
            margin-bottom: 25px;
            border-radius: 10px;
            color: white;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
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
            font-size: 11px;
            opacity: 0.9;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .job-ref-number {
            font-size: 28px;
            font-weight: 700;
            margin-top: 5px;
            letter-spacing: 3px;
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
            margin: 4px 0;
        }

        /* Section Styling */
        .section {
            margin-bottom: 18px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }

        .section-header {
            background: linear-gradient(to right, #f7fafc 0%, #edf2f7 100%);
            padding: 12px 20px;
            border-bottom: 2px solid #cbd5e0;
        }

        .section-title {
            font-size: 12px;
            font-weight: 700;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: inline-flex;
            align-items: center;
        }

        .section-title::before {
            content: '';
            display: inline-block;
            width: 4px;
            height: 14px;
            background: #3182ce;
            margin-right: 10px;
            border-radius: 2px;
        }

        .section-content {
            padding: 18px 20px;
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
            border-left: 4px solid #3182ce;
            padding: 15px 18px;
            border-radius: 4px;
            margin-top: 12px;
            line-height: 1.6;
        }

        .description-label {
            font-weight: 700;
            color: #3182ce;
            font-size: 10px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .description-text {
            color: #4a5568;
            font-size: 10px;
            line-height: 1.7;
        }

        /* Work Notes Section */
        .work-notes {
            min-height: 100px;
            border: 2px dashed #cbd5e0;
            border-radius: 6px;
            padding: 15px;
            background: #fafafa;
        }

        .work-notes-label {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 12px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notes-lines {
            display: block;
        }

        .notes-line {
            height: 20px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 8px;
        }

        /* Signature Section - Side by Side */
        .signature-section {
            margin-top: 25px;
            display: table;
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
        }

        .signature-box {
            display: table-cell;
            width: 48%;
            padding: 20px;
            border: 2px solid #cbd5e0;
            border-radius: 8px;
            vertical-align: top;
            background: #fafafa;
        }

        .signature-title {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 2px solid #3182ce;
        }

        .signature-line {
            border-bottom: 2px solid #2d3748;
            height: 45px;
            margin: 15px 0;
        }

        .signature-field {
            font-size: 9px;
            color: #718096;
            margin: 8px 0;
            font-weight: 500;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
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

        }

        .signature-box {
            display: table-cell;
            width: 48%;
            padding: 15px;
            border: 2px solid #dee2e6;
            border-radius: 6px;
            vertical-align: top;
        }

        .signature-box:first-child {
            margin-right: 4%;
        }

        .signature-title {
            font-weight: bold;
            color: #495057;
            margin-bottom: 15px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .signature-line {
            border-bottom: 2px solid #495057;
            height: 50px;
            margin-bottom: 10px;
        }

        .signature-field {
            font-size: 10px;
            color: #6c757d;
            margin-top: 5px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
        }

        .datetime-stamp {
            color: #6c757d;
            font-size: 10px;
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="page">
        {{-- Shop Header --}}
        <div class="header">
            <div class="shop-name">{{ $shop->name ?? 'Your Shop Name' }}</div>
            <div class="shop-details">
                @if($shop->address)
                    <div>📍 {{ $shop->address }}</div>
                @endif
                @if($shop->phone)
                    <div>📞 {{ $shop->phone }}</div>
                @endif
                @if($shop->email)
                    <div>✉ {{ $shop->email }}</div>
                @endif
            </div>
        </div>

        {{-- Job Card Title --}}
        <div class="job-card-title">Job Card</div>

        {{-- Job Reference Box --}}
        <div class="job-ref-box">
            <div class="job-ref-label">Job Reference</div>
            <div class="job-ref-number">{{ $job->reference_number }}</div>
            <div class="datetime-stamp">
                Created: {{ \Carbon\Carbon::parse($job->created_at)->format('d M Y, h:i A') }}
            </div>
        </div>

        {{-- Customer Information --}}
        @if($job->customer)
        <div class="section">
            <div class="section-header">Customer Information</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Customer Name</div>
                        <div class="info-value">{{ $job->customer->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone Number</div>
                        <div class="info-value">{{ $job->customer->phone ?? 'N/A' }}</div>
                    </div>
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
            <div class="section-header">Job Details</div>
            <div class="section-content">
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Job Type</div>
                        <div class="info-value">{{ $job->jobType->name ?? $job->type ?? 'General Service' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
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
                        <div class="info-label">Date Created</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($job->created_at)->format('d F Y, h:i A') }}</div>
                    </div>
                </div>

                @if($job->description)
                <div class="description-box">
                    <strong style="color: #667eea;">Job Description:</strong><br>
                    {{ $job->description }}
                </div>
                @endif
            </div>
        </div>

        {{-- Work Performed / Notes Section --}}
        <div class="section">
            <div class="section-header">Work Performed & Notes</div>
            <div class="section-content">
                <div class="work-notes">
                    <div class="work-notes-label">Technician Notes & Work Details:</div>
                    <div style="min-height: 80px;"></div>
                </div>
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-title">Technician</div>
                <div class="signature-line"></div>
                <div class="signature-field">Name: _______________________</div>
                <div class="signature-field">Date: _______________________</div>
            </div>
            <div class="signature-box">
                <div class="signature-title">Customer Acceptance</div>
                <div class="signature-line"></div>
                <div class="signature-field">Name: _______________________</div>
                <div class="signature-field">Date: _______________________</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p>This is a computer-generated job card. For any queries, please contact us.</p>
            <p style="margin-top: 5px;">Generated on {{ now()->format('d M Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>
</html>
