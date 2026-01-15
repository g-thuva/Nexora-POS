@extends('layouts.nexora')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <x-alert />

        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="alert-title">Import Errors</h4>
                <div class="text-muted">
                    <p class="mb-2">The following errors occurred during import:</p>
                    <ul class="mb-0" style="max-height: 200px; overflow-y: auto;">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="page-title mb-0">
                        {{ __('Bulk Import Orders (CSV)') }}
                    </h1>
                    <div class="btn-list">
                        <a href="{{ route('orders.import.manual') }}" class="btn btn-outline-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Manual Import
                        </a>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1"/></svg>
                            Back to Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <!-- Instructions Card -->
                <div class="card mb-3">
                    <div class="card-header bg-blue-lt">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12.01" y2="8"/><polyline points="11 12 12 12 12 16 13 16"/></svg>
                            How to Use Bulk Import
                        </h3>
                    </div>
                    <div class="card-body">
                        <ol class="mb-0">
                            <li class="mb-2"><strong>Download the CSV template</strong> using the button below.</li>
                            <li class="mb-2"><strong>Fill in your order data</strong> following the template format:
                                <ul class="mt-1">
                                    <li>Each row represents one product line item</li>
                                    <li>Multiple rows with the same invoice number will be grouped into one order</li>
                                    <li>Dates should be in YYYY-MM-DD format (e.g., 2023-06-15)</li>
                                    <li>Payment types: Cash, Card, Bank Transfer, or Credit Sales</li>
                                </ul>
                            </li>
                            <li class="mb-2"><strong>Save your file</strong> as CSV format.</li>
                            <li class="mb-2"><strong>Upload the CSV</strong> using the form below.</li>
                            <li><strong>Review results</strong> - You'll see a summary of imported orders and any errors.</li>
                        </ol>
                    </div>
                </div>

                <!-- Upload Form Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Upload CSV File</h3>
                    </div>
                    <form action="{{ route('orders.import.process-bulk') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">CSV File</label>
                                <input type="file" name="csv_file" class="form-control @error('csv_file') is-invalid @enderror"
                                       accept=".csv,.txt" required>
                                <small class="form-hint">Maximum file size: 10MB. Only CSV format is supported.</small>
                                @error('csv_file')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-check">
                                    <input type="checkbox" name="skip_stock_validation" value="1" class="form-check-input">
                                    <span class="form-check-label">
                                        <strong>Skip stock validation</strong>
                                        <span class="form-check-description">
                                            Check this if you're importing historical data and don't want to validate or reduce current stock levels.
                                            This is useful when migrating old records that have already been fulfilled.
                                        </span>
                                    </span>
                                </label>
                            </div>

                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01"/><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="alert-title">Important Notes</h4>
                                        <ul class="mb-0">
                                            <li>Products must exist in your system before importing (match by product code or name)</li>
                                            <li>Customers will be created automatically if they don't exist</li>
                                            <li>Invoice numbers must be unique</li>
                                            <li>Invalid rows will be skipped and reported</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7h-1"/><path d="M9 15l3 3l3 -3"/><path d="M12 12l0 6"/></svg>
                                Upload and Import Orders
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <!-- Download Template Card -->
                <div class="card mb-3">
                    <div class="card-header bg-success-lt">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/></svg>
                            CSV Template
                        </h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Download the CSV template with sample data to see the required format.</p>
                        <a href="{{ route('orders.import.download-template') }}" class="btn btn-success w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-1" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><path d="M7 11l5 5l5 -5"/><path d="M12 4l0 12"/></svg>
                            Download CSV Template
                        </a>
                    </div>
                </div>

                <!-- CSV Format Reference -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">CSV Column Reference</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm card-table">
                                <thead>
                                    <tr>
                                        <th>Column</th>
                                        <th>Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr><td><code>invoice_no</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>customer_name</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>customer_phone</code></td><td><span class="badge bg-yellow">Optional</span></td></tr>
                                    <tr><td><code>order_date</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>payment_type</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>product_name</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>product_code</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>quantity</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>unit_price</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>serial_number</code></td><td><span class="badge bg-yellow">Optional</span></td></tr>
                                    <tr><td><code>warranty_name</code></td><td><span class="badge bg-yellow">Optional</span></td></tr>
                                    <tr><td><code>discount_amount</code></td><td><span class="badge bg-yellow">Optional</span></td></tr>
                                    <tr><td><code>service_charges</code></td><td><span class="badge bg-yellow">Optional</span></td></tr>
                                    <tr><td><code>payment_amount</code></td><td><span class="badge bg-red">Yes</span></td></tr>
                                    <tr><td><code>import_notes</code></td><td><span class="badge bg-yellow">Optional</span></td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @if(session('imported_count'))
                <div class="card mt-3 border-success">
                    <div class="card-header bg-success-lt">
                        <h3 class="card-title text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10"/></svg>
                            Import Complete
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="h1 text-success mb-0">{{ session('imported_count') }}</div>
                        <div class="text-muted">orders imported successfully</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
