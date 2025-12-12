@php
    // Prefer controller-provided letterheadConfig. Fall back to stored config for compatibility.
    $letterheadConfig = $letterheadConfig ?? (file_exists(storage_path('app/letterhead_config.json')) ? json_decode(file_get_contents(storage_path('app/letterhead_config.json')), true) : []);
    $positions = $letterheadConfig['positions'] ?? [];
    $elementToggles = $letterheadConfig['element_toggles'] ?? [];

    // Build position map for easier template access
    $positionMap = [];
    foreach ($positions as $pos) {
        $positionMap[$pos['field']] = $pos;
    }
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Credit Sale Receipt - {{ $creditSale->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #333;
        }

        .container {
            width: 210mm;
            height: 297mm;
            padding: 20px;
            position: relative;
        }

        .letterhead-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            {{ !empty($letterheadConfig['preview_image_data']) ? 'background-image: url(' . $letterheadConfig['preview_image_data'] . '); background-size: cover; background-position: center;' : '' }}
        }

        .content {
            position: relative;
            z-index: 1;
            padding: 30px;
        }

        .receipt-header {
            @if(isset($positionMap['receipt_title']) && $elementToggles['receipt_title'] ?? true)
                position: absolute;
                left: {{ $positionMap['receipt_title']['x'] ?? 20 }}mm;
                top: {{ $positionMap['receipt_title']['y'] ?? 20 }}mm;
            @endif
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .receipt-header p {
            font-size: 10pt;
            color: #666;
        }

        .customer-section {
            @if(isset($positionMap['customer_name']) && $elementToggles['customer_name'] ?? true)
                position: absolute;
                left: {{ $positionMap['customer_name']['x'] ?? 20 }}mm;
                top: {{ $positionMap['customer_name']['y'] ?? 60 }}mm;
            @endif
            margin-bottom: 20px;
        }

        .customer-section .label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 3px;
        }

        .customer-section .value {
            font-size: 11pt;
            font-weight: bold;
        }

        .invoice-details {
            @if(isset($positionMap['invoice_details']) && $elementToggles['invoice_details'] ?? true)
                position: absolute;
                left: {{ $positionMap['invoice_details']['x'] ?? 100 }}mm;
                top: {{ $positionMap['invoice_details']['y'] ?? 60 }}mm;
            @endif
            margin-bottom: 20px;
        }

        .invoice-details .row {
            display: flex;
            margin-bottom: 5px;
            font-size: 10pt;
        }

        .invoice-details .label {
            width: 50%;
            color: #666;
        }

        .invoice-details .value {
            width: 50%;
            font-weight: bold;
        }

        .items-table {
            @if(isset($positionMap['items_table']) && $elementToggles['items_table'] ?? true)
                position: absolute;
                left: {{ $positionMap['items_table']['x'] ?? 20 }}mm;
                top: {{ $positionMap['items_table']['y'] ?? 110 }}mm;
                width: {{ $positionMap['items_table']['width'] ?? 170 }}mm;
            @else
                position: absolute;
                left: 20mm;
                top: 110mm;
                width: 170mm;
            @endif
            margin-bottom: 20px;
        }

        .items-table table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .items-table th {
            background-color: #f0f0f0;
            border-bottom: 1pt solid #999;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            border-bottom: 1pt solid #eee;
            padding: 6px 5px;
        }

        .items-table th.text-right,
        .items-table td.text-right {
            text-align: right;
        }

        .totals-section {
            @if(isset($positionMap['totals_section']) && $elementToggles['totals_section'] ?? true)
                position: absolute;
                left: {{ $positionMap['totals_section']['x'] ?? 100 }}mm;
                top: {{ $positionMap['totals_section']['y'] ?? 200 }}mm;
                width: 90mm;
            @else
                position: absolute;
                left: 100mm;
                top: 200mm;
                width: 90mm;
            @endif
            text-align: right;
            margin-bottom: 20px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 10pt;
            border-bottom: 1pt solid #eee;
        }

        .totals-row.total {
            font-weight: bold;
            font-size: 12pt;
            border-bottom: 2pt solid #333;
            padding: 8px 0;
        }

        .payment-status {
            @if(isset($positionMap['payment_status']) && $elementToggles['payment_status'] ?? true)
                position: absolute;
                left: {{ $positionMap['payment_status']['x'] ?? 20 }}mm;
                top: {{ $positionMap['payment_status']['y'] ?? 240 }}mm;
            @endif
            font-size: 11pt;
            padding: 10px;
            border: 1pt solid #ccc;
            border-radius: 3px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
        }

        .footer {
            @if(isset($positionMap['footer']) && $elementToggles['footer'] ?? true)
                position: absolute;
                left: {{ $positionMap['footer']['x'] ?? 20 }}mm;
                bottom: {{ $positionMap['footer']['y'] ?? 10 }}mm;
            @endif
            text-align: center;
            font-size: 8pt;
            color: #999;
            width: 170mm;
            border-top: 1pt solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="letterhead-background"></div>

    <div class="container">
        <div class="content">
            <!-- Receipt Header -->
            <div class="receipt-header">
                <h1>CREDIT SALE RECEIPT</h1>
                <p>Invoice #{{ $order->invoice_no }}</p>
            </div>

            <!-- Customer Information -->
            <div class="customer-section">
                <div class="label">CUSTOMER</div>
                <div class="value">{{ $creditSale->customer->name }}</div>
                @if($creditSale->customer->phone)
                    <div style="font-size: 9pt; color: #666;">{{ $creditSale->customer->phone }}</div>
                @endif
            </div>

            <!-- Invoice Details -->
            <div class="invoice-details">
                <div class="row">
                    <div class="label">Date:</div>
                    <div class="value">{{ $creditSale->sale_date->format('d/m/Y') }}</div>
                </div>
                <div class="row">
                    <div class="label">Sale Date:</div>
                    <div class="value">{{ $creditSale->sale_date->format('d/m/Y') }}</div>
                </div>
                <div class="row">
                    <div class="label">Due Date:</div>
                    <div class="value">{{ $creditSale->due_date->format('d/m/Y') }}</div>
                </div>
                <div class="row">
                    <div class="label">Status:</div>
                    <div class="value">
                        <span class="badge badge-{{ $creditSale->status === \App\Enums\CreditStatus::PAID ? 'success' : ($creditSale->status === \App\Enums\CreditStatus::PARTIAL ? 'warning' : 'danger') }}">
                            {{ ucfirst($creditSale->status->value) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="items-table">
                <table>
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $detail)
                            <tr>
                                <td>
                                    <strong>{{ $detail->product->name }}</strong>
                                    @if($detail->serial_number || $detail->warranty_name)
                                        <br><small>
                                            @if($detail->serial_number)
                                                SN: {{ $detail->serial_number }}
                                            @endif
                                            @if($detail->serial_number && $detail->warranty_name)
                                                &nbsp;|&nbsp;
                                            @endif
                                            @if($detail->warranty_name)
                                                Warranty: {{ $detail->warranty_name }}
                                            @endif
                                        </small>
                                    @endif
                                </td>
                                <td class="text-right">{{ $detail->quantity }}</td>
                                <td class="text-right">{{ number_format($detail->unitcost, 2) }}</td>
                                <td class="text-right">{{ number_format($detail->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals Section -->
            <div class="totals-section">
                <div class="totals-row">
                    <span>Subtotal:</span>
                    <span>{{ number_format($order->sub_total, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>Discount:</span>
                    <span>-{{ number_format($order->discount_amount ?? 0, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>Service Charges:</span>
                    <span>{{ number_format($order->service_charges ?? 0, 2) }}</span>
                </div>
                <div class="totals-row total">
                    <span>Total:</span>
                    <span>{{ number_format($creditSale->total_amount_formatted, 2) }}</span>
                </div>
                <div class="totals-row">
                    <span>Paid:</span>
                    <span>{{ number_format($creditSale->paid_amount_formatted, 2) }}</span>
                </div>
                <div class="totals-row total">
                    <span>Due:</span>
                    <span>{{ number_format($creditSale->due_amount_formatted, 2) }}</span>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="payment-status">
                <strong>Credit Terms:</strong> {{ $creditSale->credit_days }} days<br>
                <strong>Status:</strong> {{ ucfirst($creditSale->status->value) }}<br>
                @if($creditSale->is_overdue)
                    <strong style="color: #dc3545;">OVERDUE BY {{ $creditSale->days_overdue }} DAYS</strong>
                @endif
            </div>

            <!-- Footer -->
            <div class="footer">
                <p>Thank you for your business!</p>
                <p>Please make payment by {{ $creditSale->due_date->format('d/m/Y') }}</p>
                <p style="margin-top: 10px; font-size: 7pt;">Generated on {{ now()->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
