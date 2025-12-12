@php
    // This is the overlay version for merging with PDF letterhead
    $letterheadConfig = $letterheadConfig ?? [];
    $positions = $letterheadConfig['positions'] ?? [];
    $elementToggles = $letterheadConfig['element_toggles'] ?? [];
    $tableWidth = $letterheadConfig['table_width'] ?? 480;

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
    <title>Credit Sale Receipt Overlay</title>
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
            color: #000;
        }

        .container {
            width: 210mm;
            height: 297mm;
            position: relative;
        }

        .container {
            width: 210mm;
            height: 297mm;
            position: relative;
        }

        .field {
            position: absolute;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background-color: transparent;
            border-bottom: 1pt solid #333;
            padding: 4px 2px;
            text-align: left;
            font-weight: bold;
            font-size: 9pt;
        }

        table td {
            border-bottom: 0.5pt solid #ccc;
            padding: 3px 2px;
            font-size: 9pt;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Invoice Number -->
        @if(isset($positionMap['invoice_no']))
        <div class="field" style="position: absolute; left: {{ $positionMap['invoice_no']['x'] }}pt; top: {{ $positionMap['invoice_no']['y'] }}pt; font-size: {{ $positionMap['invoice_no']['font_size'] ?? 12 }}pt; font-weight: {{ $positionMap['invoice_no']['font_weight'] ?? 'bold' }};">
            {{ $order->invoice_no }}
        </div>
        @endif

        <!-- Invoice Date -->
        @if(isset($positionMap['invoice_date']))
        <div class="field" style="position: absolute; left: {{ $positionMap['invoice_date']['x'] }}pt; top: {{ $positionMap['invoice_date']['y'] }}pt; font-size: {{ $positionMap['invoice_date']['font_size'] ?? 11 }}pt; font-weight: {{ $positionMap['invoice_date']['font_weight'] ?? 'normal' }};">
            {{ $creditSale->sale_date->format('d/m/Y') }}
        </div>
        @endif

        <!-- Customer Name -->
        @if(isset($positionMap['customer_name']) && (!isset($elementToggles['customer_name']) || $elementToggles['customer_name']))
        <div class="field" style="position: absolute; left: {{ $positionMap['customer_name']['x'] }}pt; top: {{ $positionMap['customer_name']['y'] }}pt; font-size: {{ $positionMap['customer_name']['font_size'] ?? 12 }}pt; font-weight: {{ $positionMap['customer_name']['font_weight'] ?? 'bold' }};">
            {{ $creditSale->customer->name }}
        </div>
        @endif

        <!-- Customer Phone -->
        @if(isset($positionMap['customer_phone']) && (!isset($elementToggles['customer_phone']) || $elementToggles['customer_phone']))
        <div class="field" style="position: absolute; left: {{ $positionMap['customer_phone']['x'] }}pt; top: {{ $positionMap['customer_phone']['y'] }}pt; font-size: {{ $positionMap['customer_phone']['font_size'] ?? 11 }}pt; font-weight: {{ $positionMap['customer_phone']['font_weight'] ?? 'normal' }};">
            {{ $creditSale->customer->phone ?? '' }}
        </div>
        @endif

        <!-- Customer Address -->
        @if(isset($positionMap['customer_address']) && (!isset($elementToggles['customer_address']) || $elementToggles['customer_address']))
        <div class="field" style="position: absolute; left: {{ $positionMap['customer_address']['x'] }}pt; top: {{ $positionMap['customer_address']['y'] }}pt; font-size: {{ $positionMap['customer_address']['font_size'] ?? 10 }}pt; font-weight: {{ $positionMap['customer_address']['font_weight'] ?? 'normal' }};">
            {{ $creditSale->customer->address ?? '' }}
        </div>
        @endif

        <!-- Products Table with Payment Details -->
        @if(isset($positionMap['product_name']))
        <div class="field" style="position: absolute; left: {{ $positionMap['product_name']['x'] }}pt; top: {{ $positionMap['product_name']['y'] }}pt; width: {{ $tableWidth }}pt;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50%;">Product</th>
                        <th class="text-center" style="width: 15%;">Qty</th>
                        <th class="text-right" style="width: 17.5%;">Price</th>
                        <th class="text-right" style="width: 17.5%;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->details as $detail)
                    <tr>
                        <td style="border-bottom: none;">{{ $detail->product->name }}</td>
                        <td class="text-center" style="border-bottom: none;">{{ $detail->quantity }}</td>
                        <td class="text-right" style="border-bottom: none;">{{ number_format($detail->unitcost, 2) }}</td>
                        <td class="text-right" style="border-bottom: none;">{{ number_format($detail->total, 2) }}</td>
                    </tr>
                    @if($detail->serial_number || $detail->warranty_name || ($detail->product && $detail->product->warranty_years))
                    <tr>
                        <td colspan="4" style="font-size: 8pt; color: #888; padding: 2px 2px 3px 2px; border-bottom: none;">
                            @if($detail->serial_number)
                                <span style="margin-right: 12pt;">SN: {{ $detail->serial_number }}</span>
                            @endif
                            @if($detail->warranty_name)
                                <span>Warranty: {{ $detail->warranty_name }}</span>
                            @elseif($detail->product && $detail->product->warranty_years)
                                <span>Warranty: {{ $detail->product->warranty_years }} {{ $detail->product->warranty_years == 1 ? 'Year' : 'Years' }}</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach

                    <!-- Payment Details as Table Rows -->
                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Subtotal:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;">{{ number_format($order->sub_total, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Discount:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;">-{{ number_format($order->discount_amount ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Service Charges:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;">{{ number_format($order->service_charges ?? 0, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: bold; font-size: 10pt; border-bottom: none;">TOTAL:</td>
                        <td class="text-right" style="font-weight: bold; font-size: 10pt; border-bottom: none;">{{ number_format($creditSale->total_amount, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: normal; border-bottom: none;">Paid:</td>
                        <td class="text-right" style="font-weight: bold; border-bottom: none;">{{ number_format($creditSale->paid_amount, 2) }}</td>
                    </tr>

                    <tr>
                        <td colspan="3" class="text-right" style="font-weight: bold; font-size: 10pt; color: {{ $creditSale->due_amount > 0 ? '#d00' : '#000' }}; border-bottom: none;">DUE:</td>
                        <td class="text-right" style="font-weight: bold; font-size: 10pt; color: {{ $creditSale->due_amount > 0 ? '#d00' : '#000' }}; border-bottom: none;">{{ number_format($creditSale->due_amount, 2) }}</td>
                    </tr>

                    @if($creditSale->due_date)
                    <tr>
                        <td colspan="4" class="text-right" style="font-size: 8pt; color: #666; border-bottom: none; padding-top: 6pt;">Due Date: {{ $creditSale->due_date->format('d/m/Y') }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        @endif
    </div>
</body>
</html>
