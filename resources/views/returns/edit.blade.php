@extends('layouts.app')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid py-4">

            <div class="page-header d-print-none mb-3">
                <div class="container-fluid">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <div class="page-pretitle">INVENTORY</div>
                            <h2 class="page-title">Edit Return #{{ $returnSale->id }}</h2>
                            <p class="text-muted small">Update returned items and stock adjustments.</p>
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

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            @if(session('status'))
                                <div class="alert alert-success">{{ session('status') }}</div>
                            @endif

                            <form method="POST" action="{{ route('returns.update', $returnSale) }}">
                                @csrf
                                @method('PUT')

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="return_date" class="form-label">Return Date</label>
                                        <input type="date" name="return_date" id="return_date" value="{{ $returnSale->return_date?->format('Y-m-d') }}" class="form-control">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control">{{ old('notes', $returnSale->notes) }}</textarea>
                                </div>

                                <h5 class="mt-4">Items</h5>
                                <ul class="list-group mb-3">
                                    @foreach($returnSale->items as $item)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $item->product->name ?? '—' }}</strong>
                                                <div class="small">Qty: {{ $item->quantity }} @if($item->serial_number) — SN: {{ $item->serial_number }} @endif</div>
                                            </div>
                                            <div>{{ number_format($item->total/100, 2) }}</div>
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="d-flex">
                                    <button class="btn btn-primary">Save</button>
                                    <a href="{{ route('returns.create') }}" class="btn btn-secondary ms-2">New Return</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">Recent Returns</h6>
                            @php $recent = \App\Models\ReturnSale::latest()->limit(8)->get(); @endphp
                            @if($recent->isNotEmpty())
                                <div class="list-group">
                                    @foreach($recent as $r)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small text-muted">#{{ $r->id }} — {{ $r->return_date?->format('Y-m-d') }}</div>
                                                <div>{{ \Illuminate\Support\Str::limit($r->notes, 60) }}</div>
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
@endsection
