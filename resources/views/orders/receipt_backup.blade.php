@if(!empty($pos))
    {{-- POS Mode: Standalone HTML without layout --}}
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Receipt - {{ $order->invoice_no }}</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            body {
                background: white;
                color: black;
                font-family: monospace;
                padding: 20px;
            }
            .pos-receipt {
                width: 80mm;
                max-width: 100%;
                margin: 0 auto;
                background: white;
                padding: 8px;
                color: black;
            }
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                }
                .pos-receipt {
                    width: 80mm;
                }
            }
        </style>
    </head>
    <body>
        <div class="pos-receipt">
            <div style="text-align:center; font-weight:700; font-size:14px; color:#000;">{{ optional($order->shop)->name ?? config('app.name') }}</div>
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

            {{-- Items --}}
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
                                        <span class="warranty">Warranty: {{ $d->warranty_name }}</span>
                                    @elseif(!is_null($d->warranty_years) && $d->warranty_years > 0)
                                        <span class="warranty">Warranty: {{ $d->warranty_years }} {{ $d->warranty_years == 1 ? 'year' : 'years' }}</span>
                                    @endif
                                @endif
                                <div style="margin-top:4px;">Qty: {{ $d->quantity }}</div>
                            </div>
                        </div>
                        <div style="width:36%;text-align:right;">
                            {{ number_format(($d->total ?? 0), 2) }}
                        </div>
                    </div>
                @endforeach
            </div>

            <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
            <div style="font-size:12px;">
                <div style="display:flex;justify-content:space-between;"><div>Subtotal</div><div>{{ number_format($order->sub_total, 2) }}</div></div>
                @if(($order->discount_amount ?? 0) > 0)
                    <div style="display:flex;justify-content:space-between;"><div>Discount</div><div>-{{ number_format($order->discount_amount, 2) }}</div></div>
                @endif
                @if(($order->service_charges ?? 0) > 0)
                    <div style="display:flex;justify-content:space-between;"><div>Service</div><div>{{ number_format($order->service_charges, 2) }}</div></div>
                @endif
                <div style="display:flex;justify-content:space-between;font-weight:700;margin-top:6px;"><div>Total</div><div>{{ number_format($order->total, 2) }}</div></div>
            </div>

            <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
            <div style="text-align:center;font-size:10px;">Thank you for your purchase!</div>
        </div>

        {{-- Auto-print script for POS mode --}}
        <script>
            (function() {
                try {
                    const params = new URLSearchParams(window.location.search);
                    const autoPrint = params.get('auto') === '1';
                    
                    if (autoPrint) {
                        window.addEventListener('load', function() {
                            setTimeout(function() {
                                window.print();
                                
                                // Handle return after print
                                const returnUrl = params.get('return');
                                if (returnUrl) {
                                    window.addEventListener('afterprint', function() {
                                        window.location.href = decodeURIComponent(returnUrl);
                                    });
                                }
                            }, 500);
                        });
                    }
                } catch (e) {
                    console.error('Auto-print error:', e);
                }
            })();
        </script>
    </body>
    </html>
@else
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

        {{-- Items (POS: product details + qty below when space is tight) --}}
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
                                    <span class="warranty">Warranty: {{ $d->warranty_name }}</span>
                                @elseif(!is_null($d->warranty_years) && $d->warranty_years > 0)
                                    <span class="warranty">Warranty: {{ $d->warranty_years }} {{ $d->warranty_years == 1 ? 'year' : 'years' }}</span>
                                @endif
                            @endif
                            {{-- Quantity placed under product details to save horizontal space on narrow receipts --}}
                            <div style="margin-top:4px;">Qty: {{ $d->quantity }}</div>
                        </div>
                    </div>
                    <div style="width:36%;text-align:right;">
                        {{ number_format(($d->total ?? 0), 2) }}
                    </div>
                </div>
            @endforeach
        </div>

        <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
        <div style="font-size:12px;">
            <div style="display:flex;justify-content:space-between;"><div>Subtotal</div><div>{{ number_format($order->sub_total, 2) }}</div></div>
            @if(($order->discount_amount ?? 0) > 0)
                <div style="display:flex;justify-content:space-between;"><div>Discount</div><div>-{{ number_format($order->discount_amount, 2) }}</div></div>
            @endif
            @if(($order->service_charges ?? 0) > 0)
                <div style="display:flex;justify-content:space-between;"><div>Service</div><div>{{ number_format($order->service_charges, 2) }}</div></div>
            @endif
            <div style="display:flex;justify-content:space-between;font-weight:700;margin-top:6px;"><div>Total</div><div>{{ number_format($order->total, 2) }}</div></div>
        </div>

        <hr style="border:none;border-top:1px dashed #000;margin:6px 0;" />
        <div style="text-align:center;font-size:10px;">Thank you for your purchase!</div>
    </div>

    {{-- Auto-print script for POS mode --}}
    <script>
        (function() {
            try {
                const params = new URLSearchParams(window.location.search);
                const autoPrint = params.get('auto') === '1';
                
                if (autoPrint) {
                    window.addEventListener('load', function() {
                        setTimeout(function() {
                            window.print();
                            
                            // Handle return after print
                            const returnUrl = params.get('return');
                            if (returnUrl) {
                                window.addEventListener('afterprint', function() {
                                    window.location.href = decodeURIComponent(returnUrl);
                                });
                            }
                        }, 500);
                    });
                }
            } catch (e) {
                console.error('Auto-print error:', e);
            }
        })();
    </script>
    </body>
    </html>
@else
    {{-- Non-POS Mode: Use normal layout --}}
    @extends('layouts.nexora')
    
    @section('title', 'Receipt')
    
    @section('content')
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

                <div>
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
                            @foreach($order->details as $i => $d)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $d->product->name ?? '' }}</td>
                                    <td class="text-center">{{ $d->quantity }}</td>
                                    <td class="text-end">LKR {{ number_format($d->unitcost, 2) }}</td>
                                    <td class="text-end">LKR {{ number_format($d->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end">
                    <div style="width:320px;">
                        <div class="d-flex justify-content-between"><div>Subtotal</div><div>LKR {{ number_format($order->sub_total, 2) }}</div></div>
                        <div class="d-flex justify-content-between"><div>Discount</div><div>LKR {{ number_format(($order->discount_amount ?? 0), 2) }}</div></div>
                        <div class="d-flex justify-content-between"><div>Service</div><div>LKR {{ number_format(($order->service_charges ?? 0), 2) }}</div></div>
                        <hr />
                        <div class="d-flex justify-content-between fw-bold"><div>Total</div><div>LKR {{ number_format($order->total, 2) }}</div></div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button class="btn btn-primary me-2" onclick="window.print();">Print</button>
                    <a href="{{ route('orders.download-pdf-bill', $order->id) }}" target="_blank" class="btn btn-outline-secondary">Download PDF</a>
                </div>
            </div>
        </div>
    </div>
@endif
@endsection

@push('page-styles')
<style>
    /* Ensure printed page matches 80mm thermal receipt width */
    @page { size: 80mm auto; margin: 0; }

    @media print {
        body * { visibility: hidden; }
        .card, .card * { visibility: visible; }
        .card { position: absolute; left: 0; top: 0; width: 100%; }
        /* If POS layout is used, ensure pos-receipt is visible for printing */
        .pos-receipt, .pos-receipt * { visibility: visible !important; display: block !important; }
        .pos-receipt { position: absolute !important; left: 0 !important; top: 0 !important; width: 80mm !important; min-width: 80mm !important; background: #fff !important; }
        html, body { width: 80mm !important; height: auto !important; margin: 0 !important; padding: 0 !important; }
        /* Reduce page margins in browsers that add default margins */
        @page { margin: 0; }
    }
</style>
@endpush

@push('page-scripts')
<script>
    // If opened with ?auto=1, auto trigger print on load and attempt to close window after printing.
    (function() {
        try {
            var params = new URLSearchParams(window.location.search);
            if (params.get('auto') === '1') {
                // Delay briefly to allow fonts/images to settle in some browsers
                window.addEventListener('load', function() {
                    try {
                        setTimeout(function() {
                            // Trigger print
                            window.print();
                        }, 150);
                        // When printing completes, navigate back to the return URL if present
                        try {
                            window.addEventListener('afterprint', function() {
                                try {
                                    const params = new URLSearchParams(window.location.search);
                                    const ret = params.get('return');
                                    if (ret) {
                                        window.location.href = decodeURIComponent(ret);
                                    } else {
                                        // fallback to history back
                                        try { history.back(); } catch (e) { /* ignore */ }
                                    }
                                } catch (e) {
                                    try { history.back(); } catch (e) { /* ignore */ }
                                }
                            });
                        } catch (e) {
                            // ignore
                        }

                        // Also navigate back when user presses Escape
                        document.addEventListener('keydown', function escHandler(ev) {
                            if (ev.key === 'Escape' || ev.key === 'Esc') {
                                try {
                                    const params = new URLSearchParams(window.location.search);
                                    const ret = params.get('return');
                                    if (ret) {
                                        window.location.href = decodeURIComponent(ret);
                                    } else {
                                        history.back();
                                    }
                                } catch (e) {
                                    try { history.back(); } catch (e) { /* ignore */ }
                                }
                            }
                        });
                    } catch (e) {
                        console.error('Auto print failed', e);
                    }
                });
            }
        } catch (e) {
            // ignore
        }
    })();
</script>
@endpush
@endsection
@endif