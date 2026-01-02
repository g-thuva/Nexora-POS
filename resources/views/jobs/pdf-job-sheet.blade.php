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
            margin: 20mm;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.6;
            color: #333;
        }

        .page {
            width: 100%;
            background: white;
        }

.header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
            border-radius: 8px;
            margin-bottom: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .shop-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .shop-details {
            font-size: 11px;
            opacity: 0.95;
            line-height: 1.5;
        }

        .job-card-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #667eea;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .job-ref-box {
            background: #f8f9fa;
            border: 2px solid #667eea;
            border-radius: 6px;
            padding: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .job-ref-label {
            font-size: 12px;
            color: #666;
            font-weight: bold;
            text-transform: uppercase;
        }

        .job-ref-number {
            font-size: 22px;
            font-weight: bold;
            color: #667eea;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        .section {
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
        }

        .section-header {
            background: #667eea;
            color: white;
            padding: 12px 15px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-content {
            padding: 15px;
            background: white;
        }

        .info-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            padding: 8px 12px;
            font-weight: bold;
            color: #495057;
            width: 35%;
            background: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .info-value {
            display: table-cell;
            padding: 8px 12px;
            color: #212529;
            border-bottom: 1px solid #dee2e6;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-in_progress {
            background: #cfe2ff;
            color: #084298;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-on_hold {
            background: #f8d7da;
            color: #842029;
        }

        .status-cancelled {
            background: #e2e3e5;
            color: #41464b;
        }

        .description-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
            line-height: 1.7;
        }

        .work-notes {
            min-height: 120px;
            border: 1px dashed #adb5bd;
            border-radius: 4px;
            padding: 15px;
            background: #fcfcfc;
            margin-top: 10px;
        }

        .work-notes-label {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 10px;
        }

        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
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
