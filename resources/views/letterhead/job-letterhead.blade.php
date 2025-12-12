@extends('layouts.nexora')

@section('content')
<div class="page-wrapper">
    <div class="container-fluid py-4">

<style>
.draggable-element {
    position: absolute;
    padding: 8px 12px;
    background: rgba(255, 255, 255, 0.95);
    border: 2px solid #007bff;
    cursor: move;
    z-index: 100;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-family: Arial, sans-serif;
    font-size: 11px;
    white-space: nowrap;
    user-select: none;
    transition: all 0.2s ease;
}

.draggable-element:hover {
    box-shadow: 0 6px 20px rgba(0,123,255,0.4);
    border-color: #0056b3;
    transform: scale(1.02);
}

.draggable-element.selected {
    border-color: #28a745;
    box-shadow: 0 6px 20px rgba(40,167,69,0.5);
    background: rgba(40, 167, 69, 0.1);
}

.position-editor {
    position: relative;
    display: inline-block;
    margin: 20px 0;
}

.pdf-canvas-container:hover .positioning-grid {
    opacity: 0.6 !important;
}
</style>

<div class="page-body">
    <div class="container-fluid">
        <x-alert/>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📄 Job Sheet Letterhead Configuration</h3>
                        <div class="card-actions">
                            <a href="{{ route('jobs.index') }}" class="btn btn-outline-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M9 14l-4 -4l4 -4"/>
                                    <path d="M5 10h11a4 4 0 1 1 0 8h-1"/>
                                </svg>
                                Back to Jobs
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5>ℹ️ Job Sheet Letterhead</h5>
                            <p class="mb-0">Configure a separate letterhead template specifically for job sheets. This is independent from your order invoice letterhead and allows you to have different designs for jobs and orders.</p>
                        </div>

                        <!-- Upload Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h4>1. Upload Job Sheet Letterhead</h4>
                                <form id="jobLetterheadForm" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Select Letterhead File (PNG, JPG, JPEG, PDF)</label>
                                        <input type="file" class="form-control" name="letterhead" accept="image/*,.pdf" required>
                                        <div class="form-text">
                                            <strong>Images:</strong> Recommended size: 595px x 842px (A4 at 72 DPI)<br>
                                            <strong>PDF:</strong> Single page A4 PDF letterhead (max 5MB)
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"/>
                                            <path d="M12 11l0 6"/>
                                            <path d="M9 14l3 -3l3 3"/>
                                        </svg>
                                        Upload Job Letterhead
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <h4>2. Current Job Letterhead</h4>
                                <div id="currentJobLetterhead">
                                    @if(isset($config['letterhead_file']) && $config['letterhead_file'])
                                        @php
                                            $fileExtension = pathinfo($config['letterhead_file'], PATHINFO_EXTENSION);
                                            $isPdf = strtolower($fileExtension) === 'pdf';
                                        @endphp

                                        @if($isPdf && isset($config['preview_image']))
                                            <img src="{{ asset('letterheads/' . $config['preview_image']) }}" alt="Job Letterhead Preview" class="img-fluid border" style="max-width: 100%; height: auto;">
                                        @elseif(!$isPdf)
                                            <img src="{{ asset('letterheads/' . $config['letterhead_file']) }}" alt="Job Letterhead" class="img-fluid border" style="max-width: 100%; height: auto;">
                                        @else
                                            <div class="alert alert-warning">PDF letterhead uploaded but preview not available.</div>
                                        @endif
                                        <div class="mt-2">
                                            <small class="text-muted">File: {{ $config['letterhead_file'] }}</small>
                                        </div>
                                    @else
                                        <div class="alert alert-secondary">
                                            <p class="mb-0">No job letterhead uploaded yet.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Position Configuration Section -->
                        @if(isset($config['letterhead_file']) && $config['letterhead_file'])
                        <div class="row">
                            <div class="col-12">
                                <h4>3. Position Elements on Letterhead</h4>
                                <p class="text-muted mb-3">Click and drag the elements on the preview to position them precisely on your job sheet letterhead.</p>

                                <div class="position-editor-container">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="position-editor" id="positionEditor">
                                @php
                                                    $letterheadType = $config['letterhead_type'] ?? 'image';
                                                    $previewImage = $config['preview_image'] ?? null;
                                                    $hasPdfPreview = false;
                                                    $positioningImage = '';

                                                    if ($letterheadType === 'pdf') {
                                                        if ($previewImage && file_exists(public_path('letterheads/' . $previewImage))) {
                                                            $positioningImage = 'letterheads/' . $previewImage;
                                                            $hasPdfPreview = true;
                                                        } else {
                                                            // No preview available - will use blank canvas with PDF embed
                                                            $hasPdfPreview = false;
                                                        }
                                                    } else {
                                                        $positioningImage = 'letterheads/' . $config['letterhead_file'];
                                                        $hasPdfPreview = true;
                                                    }
                                                @endphp

                                                @if($letterheadType === 'pdf' && !$hasPdfPreview)
                                                    {{-- PDF without preview - show PDF embed with overlay --}}
                                                    <div class="alert alert-info mb-3">
                                                        <strong>📄 PDF Letterhead Positioning Mode</strong><br>
                                                        Your PDF is loaded below. Position the elements, and they will appear in the exact same locations on your final job sheet PDFs.
                                                        The grid and rulers help with precise positioning.
                                                    </div>

                                                    <div class="pdf-canvas-container" style="position: relative; display: inline-block; border: 3px solid #007bff; background: #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.2); border-radius: 8px; overflow: visible;">
                                                        <div class="canvas-indicator" style="position: absolute; top: -35px; left: 0; background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; z-index: 15;">
                                                            📋 Job Sheet Canvas (595×842px)
                                                        </div>

                                                        {{-- PDF Embed --}}
                                                        <div style="position: absolute; width: 595px; height: 842px; z-index: 0;">
                                                            <embed src="{{ asset('letterheads/' . $config['letterhead_file']) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                                   type="application/pdf"
                                                                   width="595"
                                                                   height="842"
                                                                   style="border: none; pointer-events: none;">
                                                        </div>

                                                        <div id="letterheadImage"
                                                             style="width: 595px; height: 842px;
                                                                    position: relative;
                                                                    overflow: visible;
                                                                    border-radius: 5px;
                                                                    border: 1px solid rgba(0,123,255,0.3);
                                                                    background: transparent;">

                                                            <div class="positioning-grid" id="positioningGrid" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                                                        background-image:
                                                                            linear-gradient(rgba(0,123,255,0.15) 1px, transparent 1px),
                                                                            linear-gradient(90deg, rgba(0,123,255,0.15) 1px, transparent 1px),
                                                                            linear-gradient(rgba(255,0,0,0.1) 1px, transparent 1px),
                                                                            linear-gradient(90deg, rgba(255,0,0,0.1) 1px, transparent 1px);
                                                                        background-size: 25px 25px, 25px 25px, 5px 5px, 5px 5px;
                                                                        opacity: 0.3;
                                                                        z-index: 1;
                                                                        pointer-events: none;
                                                                        transition: opacity 0.3s ease;"></div>

                                                            <!-- Ruler markers -->
                                                            <div class="ruler-top" style="position: absolute; top: -20px; left: 0; right: 0; height: 20px; background: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; z-index: 10;">
                                                                @for($i = 0; $i <= 595; $i += 50)
                                                                    <div style="position: absolute; left: {{ $i }}px; top: 5px; font-size: 8px; color: #000;">{{ $i }}</div>
                                                                    <div style="position: absolute; left: {{ $i }}px; top: 0; width: 1px; height: 20px; background: #000;"></div>
                                                                @endfor
                                                            </div>

                                                            <div class="ruler-left" style="position: absolute; left: -20px; top: 0; bottom: 0; width: 20px; background: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; z-index: 10;">
                                                                @for($i = 0; $i <= 842; $i += 50)
                                                                    <div style="position: absolute; top: {{ $i }}px; left: 2px; font-size: 8px; color: #000; writing-mode: vertical-rl;">{{ $i }}</div>
                                                                    <div style="position: absolute; top: {{ $i }}px; left: 0; width: 20px; height: 1px; background: #000;"></div>
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- Image or PDF with preview --}}

                                                <div class="pdf-canvas-container" style="position: relative; display: inline-block; border: 3px solid #007bff; background: #fff; box-shadow: 0 8px 24px rgba(0,0,0,0.2); border-radius: 8px; overflow: visible;">
                                                    <div class="canvas-indicator" style="position: absolute; top: -35px; left: 0; background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; z-index: 15;">
                                                        📋 Job Sheet Canvas (595×842px)
                                                    </div>

                                                    <div id="letterheadImage"
                                                         style="width: 595px; height: 842px;
                                                                background-image: url('{{ asset($positioningImage) }}?t={{ time() }}');
                                                                background-size: cover;
                                                                background-repeat: no-repeat;
                                                                background-position: center center;
                                                                position: relative;
                                                                overflow: visible;
                                                                border-radius: 5px;
                                                                border: 1px solid rgba(0,123,255,0.3);">

                                                        <div class="positioning-grid" id="positioningGrid" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;
                                                                    background-image:
                                                                        linear-gradient(rgba(0,123,255,0.15) 1px, transparent 1px),
                                                                        linear-gradient(90deg, rgba(0,123,255,0.15) 1px, transparent 1px),
                                                                        linear-gradient(rgba(255,0,0,0.1) 1px, transparent 1px),
                                                                        linear-gradient(90deg, rgba(255,0,0,0.1) 1px, transparent 1px);
                                                                    background-size: 25px 25px, 25px 25px, 5px 5px, 5px 5px;
                                                                    opacity: 0.3;
                                                                    z-index: 1;
                                                                    pointer-events: none;
                                                                    transition: opacity 0.3s ease;"></div>

                                                        <!-- Ruler markers -->
                                                        <div class="ruler-top" style="position: absolute; top: -20px; left: 0; right: 0; height: 20px; background: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; z-index: 10;">
                                                            @for($i = 0; $i <= 595; $i += 50)
                                                                <div style="position: absolute; left: {{ $i }}px; top: 5px; font-size: 8px; color: #000;">{{ $i }}</div>
                                                                <div style="position: absolute; left: {{ $i }}px; top: 0; width: 1px; height: 20px; background: #000;"></div>
                                                            @endfor
                                                        </div>

                                                        <div class="ruler-left" style="position: absolute; left: -20px; top: 0; bottom: 0; width: 20px; background: #f8f9fa; border: 1px solid #dee2e6; font-size: 10px; z-index: 10;">
                                                            @for($i = 0; $i <= 842; $i += 50)
                                                                <div style="position: absolute; top: {{ $i }}px; left: 2px; font-size: 8px; color: #000; writing-mode: vertical-rl;">{{ $i }}</div>
                                                                <div style="position: absolute; top: {{ $i }}px; left: 0; width: 20px; height: 1px; background: #000;"></div>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="canvas-controls mt-2 mb-2" style="display: flex; gap: 10px; align-items: center;">
                                                    <button type="button" class="btn btn-sm btn-outline-primary" id="toggleGrid">
                                                        <i class="fas fa-th"></i> Toggle Grid
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success" id="toggleRulers">
                                                        <i class="fas fa-ruler"></i> Toggle Rulers
                                                    </button>
                                                    <span class="text-muted" style="font-size: 12px;">💡 Hover canvas to enhance grid visibility</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Element Properties</h5>
                                                </div>
                                                <div class="card-body">
                                                    <div id="elementProperties">
                                                        <p class="text-muted">Select an element to edit its properties</p>
                                                    </div>
                                                    <button type="button" class="btn btn-success w-100 mt-3" id="savePositions">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                            <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-16a2 2 0 0 1 2 -2"/>
                                                            <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"/>
                                                            <path d="M14 4l0 4l-6 0l0 -4"/>
                                                        </svg>
                                                        Save Positions
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        @endif

                        <!-- Test Section -->
                        <div class="row">
                            <div class="col-12">
                                <h4>{{ isset($config['letterhead_file']) && $config['letterhead_file'] ? '4' : '3' }}. Test Job Sheet PDF</h4>
                                @if($testJobId)
                                    <p class="text-muted">Generate a test PDF with your configured letterhead to see how it looks.</p>
                                    <a href="{{ route('jobs.pdf-job-sheet', $testJobId) }}" class="btn btn-success" target="_blank">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"/><path d="M7 11l5 5l5 -5"/><path d="M12 4l0 12"/></svg>
                                        Download Test Job Sheet PDF
                                    </a>
                                @else
                                    <div class="alert alert-warning">
                                        <p class="mb-0">No jobs available for testing. Create a job first to test the PDF generation.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
// Upload form handler
document.getElementById('jobLetterheadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

    fetch('{{ route("job-letterhead.upload") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Job letterhead uploaded successfully!');
            window.location.reload();
        } else {
            alert('Upload failed: ' + data.message);
        }
    })
    .catch(error => {
        alert('Upload error: ' + error.message);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Positioning functionality
@if(isset($config['letterhead_file']) && $config['letterhead_file'])
let selectedElement = null;
let isDragging = false;
let offsetX, offsetY;

const elements = {
    job_reference: { label: 'Job Reference', defaultX: 50, defaultY: 100 },
    customer_name: { label: 'Customer Name', defaultX: 50, defaultY: 150 },
    customer_phone: { label: 'Customer Phone', defaultX: 50, defaultY: 180 },
    customer_address: { label: 'Customer Address', defaultX: 50, defaultY: 210 },
    job_type: { label: 'Job Type', defaultX: 400, defaultY: 100 },
    estimated_duration: { label: 'Estimated Duration', defaultX: 400, defaultY: 130 },
    status: { label: 'Status', defaultX: 400, defaultY: 160 },
    description: { label: 'Description', defaultX: 50, defaultY: 280 }
};

// Load saved positions
fetch('{{ route("job-letterhead.positions") }}')
    .then(response => response.json())
    .then(data => {
        const savedPositions = data.positions || {};

        Object.keys(elements).forEach(key => {
            const elem = elements[key];
            const saved = savedPositions[key] || {};

            const div = document.createElement('div');
            div.className = 'draggable-element';
            div.id = key;
            div.textContent = elem.label;
            div.style.left = (saved.x || elem.defaultX) + 'px';
            div.style.top = (saved.y || elem.defaultY) + 'px';
            div.style.fontSize = (saved.fontSize || 11) + 'px';

            div.addEventListener('mousedown', startDrag);
            div.addEventListener('click', selectElement);

            document.getElementById('letterheadImage').appendChild(div);
        });
    });

function startDrag(e) {
    isDragging = true;
    selectedElement = e.target;

    const rect = e.target.getBoundingClientRect();
    const parentRect = e.target.parentElement.getBoundingClientRect();

    offsetX = e.clientX - rect.left;
    offsetY = e.clientY - rect.top;

    e.target.classList.add('selected');
    selectElement(e);

    document.addEventListener('mousemove', drag);
    document.addEventListener('mouseup', stopDrag);
}

function drag(e) {
    if (!isDragging || !selectedElement) return;

    const parentRect = selectedElement.parentElement.getBoundingClientRect();
    let newX = e.clientX - parentRect.left - offsetX;
    let newY = e.clientY - parentRect.top - offsetY;

    // Boundaries
    newX = Math.max(0, Math.min(newX, 595 - selectedElement.offsetWidth));
    newY = Math.max(0, Math.min(newY, 842 - selectedElement.offsetHeight));

    selectedElement.style.left = newX + 'px';
    selectedElement.style.top = newY + 'px';

    updateElementProperties();
}

function stopDrag() {
    isDragging = false;
    document.removeEventListener('mousemove', drag);
    document.removeEventListener('mouseup', stopDrag);
}

function selectElement(e) {
    document.querySelectorAll('.draggable-element').forEach(el => {
        el.classList.remove('selected');
    });

    e.target.classList.add('selected');
    selectedElement = e.target;

    updateElementProperties();
}

function updateElementProperties() {
    if (!selectedElement) return;

    const x = parseInt(selectedElement.style.left);
    const y = parseInt(selectedElement.style.top);
    const fontSize = parseInt(selectedElement.style.fontSize);

    document.getElementById('elementProperties').innerHTML = `
        <div class="mb-3">
            <label class="form-label"><strong>${selectedElement.textContent}</strong></label>
        </div>
        <div class="mb-3">
            <label class="form-label">X Position</label>
            <input type="number" class="form-control" id="propX" value="${x}" min="0" max="595">
        </div>
        <div class="mb-3">
            <label class="form-label">Y Position</label>
            <input type="number" class="form-control" id="propY" value="${y}" min="0" max="842">
        </div>
        <div class="mb-3">
            <label class="form-label">Font Size</label>
            <input type="number" class="form-control" id="propFontSize" value="${fontSize}" min="8" max="20">
        </div>
    `;

    document.getElementById('propX').addEventListener('input', function() {
        selectedElement.style.left = this.value + 'px';
    });

    document.getElementById('propY').addEventListener('input', function() {
        selectedElement.style.top = this.value + 'px';
    });

    document.getElementById('propFontSize').addEventListener('input', function() {
        selectedElement.style.fontSize = this.value + 'px';
    });
}

// Save positions
document.getElementById('savePositions').addEventListener('click', function() {
    const positions = {};

    document.querySelectorAll('.draggable-element').forEach(elem => {
        positions[elem.id] = {
            x: parseInt(elem.style.left),
            y: parseInt(elem.style.top),
            fontSize: parseInt(elem.style.fontSize)
        };
    });

    fetch('{{ route("job-letterhead.save-positions") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ positions })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Positions saved successfully!');
        } else {
            alert('Failed to save positions');
        }
    });
});

// Grid and ruler toggles
document.getElementById('toggleGrid').addEventListener('click', function() {
    const grid = document.getElementById('positioningGrid');
    grid.style.display = grid.style.display === 'none' ? 'block' : 'none';
});

document.getElementById('toggleRulers').addEventListener('click', function() {
    const rulers = document.querySelectorAll('.ruler-top, .ruler-left');
    rulers.forEach(ruler => {
        ruler.style.display = ruler.style.display === 'none' ? 'block' : 'none';
    });
});
@endif
</script>
@endpush

    </div>
</div>
@endsection
