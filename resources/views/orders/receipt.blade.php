@if(!empty($pos))
    {{-- POS Mode: Standalone HTML without layout --}}
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Receipt - {{ $order->invoice_no }}</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { background: white; color: black; font-family: monospace; padding: 20px; font-weight: bold; }
            .pos-receipt { width: 80mm; max-width: 100%; margin: 0 auto; background: white; padding: 8px; color: black; }
            @media print {
                @page { margin: 0; size: 80mm auto; }
                body { margin: 0; padding: 0; background: white; color: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; font-weight: bold; }
                .pos-receipt { width: 80mm; margin: 0; padding: 4mm; color: #000; font-weight: bold; }
                * { color: #000 !important; font-weight: bold !important; }
            }
        </style>
    </head>
    <body>
        <div class="pos-receipt">
            <div style="text-align:center; font-weight:700; font-size:14px;">{{ optional($order->shop)->name ?? config('app.name') }}</div>
            @if(optional($order->shop)->address)
                <div style="text-align:center; font-size:10px;">{{ optional($order->shop)->address }}</div>
            @endif
            @if(optional($order->shop)->phone)
                <div style="text-align:center; font-size:10px;">{{ optional($order->shop)->phone }}</div>
            @endif
            <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
            <div style="display:flex;justify-content:space-between;font-size:11px;">
                <div>Receipt #: {{ $order->invoice_no }}</div>
                <div>{{ $order->order_date->format('d-m-Y') }}</div>
            </div>
            <div style="font-size:11px;margin-top:6px;">Customer: {{ $order->customer->name ?? 'Walk-In' }}</div>
            <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />

            <div style="font-size:11px;">
                @foreach($order->details as $d)
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                        <div style="width:62%;">
                            <div style="font-weight:600;">{{ Str::limit($d->product->name ?? '', 36) }}</div>
                            <div style="font-size:10px;color:#000;">
                                @if(!empty($d->serial_number))
                                    S/N: {{ $d->serial_number }}
                                @endif
                                @if($d->warranty_name || (!is_null($d->warranty_years) && $d->warranty_years > 0))
                                    @if(!empty($d->serial_number))<br>@endif
                                    @if($d->warranty_name)
                                        Warranty: {{ $d->warranty_name }}
                                    @elseif(!is_null($d->warranty_years) && $d->warranty_years > 0)
                                        Warranty: {{ $d->warranty_years }} {{ $d->warranty_years == 1 ? 'year' : 'years' }}
                                    @endif
                                @endif
                                <div style="margin-top:4px;">Qty: {{ $d->quantity }}</div>
                            </div>
                        </div>
                        <div style="width:36%;text-align:right;">LKR {{ number_format(($d->total ?? 0), 2) }}</div>
                    </div>
                @endforeach
            </div>

            <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
            <div style="font-size:12px;">
                <div style="display:flex;justify-content:space-between;"><div>Subtotal</div><div>LKR {{ number_format($order->sub_total, 2) }}</div></div>
                <div style="display:flex;justify-content:space-between;"><div>Discount</div><div>-LKR {{ number_format($order->discount_amount ?? 0, 2) }}</div></div>
                <div style="display:flex;justify-content:space-between;"><div>Service Charges</div><div>LKR {{ number_format($order->service_charges ?? 0, 2) }}</div></div>
                <div style="display:flex;justify-content:space-between;font-weight:700;margin-top:6px;border-top:1px dashed #000;padding-top:6px;"><div>Total</div><div>LKR {{ number_format($order->total, 2) }}</div></div>
            </div>

            <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
            <div style="text-align:center;font-size:10px;">Thank you for your purchase!</div>
        </div>

        <script>
            (function() {
                try {
                    const params = new URLSearchParams(window.location.search);
                    if (params.get('auto') === '1') {
                        window.addEventListener('load', function() {
                            setTimeout(function() {
                                window.print();
                                const returnUrl = params.get('return');

                                // Close/redirect after print dialog closes
                                window.addEventListener('afterprint', function() {
                                    if (returnUrl) {
                                        window.location.href = decodeURIComponent(returnUrl);
                                    } else {
                                        window.close();
                                        // If close fails (popup blocker), go back
                                        setTimeout(function() {
                                            window.history.back();
                                        }, 100);
                                    }
                                });

                                // Fallback: also close on ESC key
                                document.addEventListener('keydown', function(e) {
                                    if (e.key === 'Escape' || e.key === 'Esc') {
                                        if (returnUrl) {
                                            window.location.href = decodeURIComponent(returnUrl);
                                        } else {
                                            window.close() || window.history.back();
                                        }
                                    }
                                });
                            }, 500);
                        });
                    }
                } catch (e) { console.error('Auto-print error:', e); }
            })();
        </script>
    </body>
    </html>
@else
    {{-- Non-POS Mode: Use normal layout --}}
    @php
        $layoutData = [
            'order' => $order,
            'letterheadConfig' => $letterheadConfig ?? null
        ];
    @endphp
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Receipt - {{ $order->invoice_no }}</title>
    </head>
    <body>
        @include('layouts.body.header')
        @include('layouts.body.navbar')

        <div class="page-wrapper">
            <div class="container py-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <h4 class="mb-0">{{ optional($order->shop)->name ?? config('app.name') }}</h4>
                                <div class="text-muted small">{{ optional($order->shop)->address ?? '' }}</div>
                                <div class="text-muted small">{{ optional($order->shop)->phone ?? '' }}</div>
                            </div>
                            <div class="text-end">
                                <h5 class="mb-0">Receipt</h5>
                                <div class="text-muted">#{{ $order->invoice_no }}</div>
                                <div class="text-muted">{{ $order->order_date->format('d-m-Y H:i') }}</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Customer</strong>
                            <div>{{ $order->customer->name ?? 'Walk-In Customer' }}</div>
                            @if(!empty($order->customer->phone))
                                <div class="text-muted small">{{ $order->customer->phone }}</div>
                            @endif
                        </div>

                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->details as $index => $d)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            {{ $d->product->name ?? '' }}
                                            @if(!empty($d->serial_number))
                                                <br><small class="text-muted">S/N: {{ $d->serial_number }}</small>
                                            @endif
                                            @if($d->warranty_name || (!is_null($d->warranty_years) && $d->warranty_years > 0))
                                                <br><small class="text-muted">
                                                    @if($d->warranty_name)
                                                        Warranty: {{ $d->warranty_name }}
                                                    @elseif(!is_null($d->warranty_years) && $d->warranty_years > 0)
                                                        Warranty: {{ $d->warranty_years }} {{ $d->warranty_years == 1 ? 'year' : 'years' }}
                                                    @endif
                                                </small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $d->quantity }}</td>
                                        <td class="text-end">{{ number_format(($d->unitcost ?? 0) / 100, 2) }}</td>
                                        <td class="text-end">{{ number_format(($d->total ?? 0) / 100, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                                    <td class="text-end">{{ number_format($order->sub_total, 2) }}</td>
                                </tr>
                                @if(($order->discount_amount ?? 0) > 0)
                                    <tr>
                                        <td colspan="4" class="text-end">Discount</td>
                                        <td class="text-end">-{{ number_format($order->discount_amount, 2) }}</td>
                                    </tr>
                                @endif
                                @if(($order->service_charges ?? 0) > 0)
                                    <tr>
                                        <td colspan="4" class="text-end">Service Charges</td>
                                        <td class="text-end">{{ number_format($order->service_charges, 2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total</strong></td>
                                    <td class="text-end"><strong>{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
@endif
