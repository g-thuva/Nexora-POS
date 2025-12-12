@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid py-4">

            <div class="page-header d-print-none mb-3">
                <div class="container-fluid">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">INVENTORY</div>
                            <h2 class="page-title">Returns Management</h2>
                            <p class="text-muted small">Record returned items and restore stock to inventory.</p>
                        </div>
                        <div class="col-auto ms-auto d-print-none">
                            <div class="btn-list">
                                <a href="{{ route('returns.create') }}" class="btn btn-outline-primary">New Return</a>
                                <a href="{{ url('/returns') }}" class="btn btn-primary ms-2">Return List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">TOTAL RETURNS</div>
                        <div class="h5">LKR {{ number_format(($totalReturns ?? 0) / 100, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">ITEMS RETURNED</div>
                        <div class="h5">{{ $itemsReturned ?? 0 }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">THIS MONTH</div>
                        <div class="h5">LKR {{ number_format(($monthTotal ?? 0) / 100, 2) }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card p-3">
                        <div class="text-muted small">THIS WEEK</div>
                        <div class="h5">LKR {{ number_format(($weekTotal ?? 0) / 100, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('returns.store') }}">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="customer_id" class="form-label">Customer (optional)</label>
                                        <input type="number" name="customer_id" id="customer_id" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="return_date" class="form-label">Return Date</label>
                                        <input type="date" name="return_date" id="return_date" class="form-control" value="{{ now()->toDateString() }}">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Items</label>
                                    <div id="items">
                                        <div class="row item-row mb-2">
                                            <div class="col-md-6 mb-2">
                                                <select name="items[0][product_id]" class="form-control">
                                                    @foreach($products as $p)
                                                        <option value="{{ $p->id }}">{{ $p->name }} @if($p->code) ({{ $p->code }}) @endif</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2 mb-2">
                                                <input type="number" name="items[0][quantity]" value="1" class="form-control" min="1">
                                            </div>
                                            <div class="col-md-4 mb-2">
                                                <input type="text" name="items[0][serial_number]" class="form-control" placeholder="Serial (optional)">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" id="add-item" class="btn btn-sm btn-outline-primary mt-2">Add item</button>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-secondary me-2" type="reset">Reset</button>
                                    <button class="btn btn-primary" type="submit">Save Return</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Recent Returns</h6>
                                <div>
                                    <a href="{{ route('returns.create') }}" class="btn btn-sm btn-outline-primary">New</a>
                                    <a href="{{ url('/returns') }}" class="btn btn-sm btn-primary ms-1">List</a>
                                </div>
                            </div>

                            @if(!empty($recentReturns) && safe_count($recentReturns))
                                <div class="list-group">
                                    @foreach($recentReturns as $r)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-muted">#{{ $r->id }} — {{ $r->return_date?->format('Y-m-d') }}</div>
                                                <div>LKR {{ number_format(($r->total ?? 0) / 100, 2) }} • {{ $r->items_sum_quantity ?? 0 }} items</div>
                                            </div>
                                            <div>
                                                <a href="{{ route('returns.edit', $r) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted small">No recent returns</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('page-scripts')
    <script>
        (function(){
            let idx = 1;
            document.getElementById('add-item').addEventListener('click', function(){
                const template = document.querySelector('.item-row').cloneNode(true);
                template.querySelectorAll('select, input').forEach(function(el){
                    if(el.name) el.name = el.name.replace(/items\[0\]/, 'items['+idx+']');
                });
                document.getElementById('items').appendChild(template);
                idx++;
            });
        })();
    </script>
    @endpush
@endsection
