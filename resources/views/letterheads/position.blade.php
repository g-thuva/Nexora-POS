@php
    $csrf = csrf_token();
@endphp
<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ $csrf }}">
    <title>Letterhead Positioning</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
        .container { padding: 12px; }
        .preview-wrap { position: relative; max-width: 800px; margin: 0 auto; border: 1px solid #ddd; }
        .preview-frame { width: 100%; height: 1120px; border: none; }
        .overlay { position: absolute; left: 0; top: 0; right: 0; bottom: 0; pointer-events: none; }
        .click-catcher { position: absolute; left: 0; top: 0; right: 0; bottom: 0; background: rgba(0,0,0,0); }
        .info { margin-top: 12px; }
        button { padding: 8px 12px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Letterhead Positioning (A4 canvas)</h2>

    <p>Click on the preview to set the top-left origin for overlay content. The coordinates will be saved in millimetres relative to A4.</p>

    <div class="preview-wrap" id="previewWrap">
        @if($previewImage)
            <img id="previewImg" src="{{ $previewImage }}" style="width:100%; display:block;" alt="letterhead preview" />
        @elseif($letterheadPdf)
            <object id="previewObj" data="{{ str_replace(public_path(), '', $letterheadPdf) }}" type="application/pdf" class="preview-frame">
                <p>PDF preview not supported. Please provide a preview image.</p>
            </object>
        @else
            <div style="padding:60px;text-align:center;color:#777">No letterhead found for this shop.</div>
        @endif
        <div class="overlay" id="overlay">
            <div class="click-catcher" id="clickCatcher"></div>
        </div>
    </div>

    <div class="info">
        <p>Selected offset: <span id="selected">(none)</span></p>
        <button id="saveBtn" disabled>Save offset</button>
        <button id="clearBtn">Clear saved offset</button>
        <p id="status" style="color:green"></p>
    </div>
</div>

<script>
(function(){
    const previewWrap = document.getElementById('previewWrap');
    const clickCatcher = document.getElementById('clickCatcher');
    const selectedEl = document.getElementById('selected');
    const saveBtn = document.getElementById('saveBtn');
    const clearBtn = document.getElementById('clearBtn');
    const status = document.getElementById('status');

    // A4 in mm
    const a4WidthMm = 210.0;
    const a4HeightMm = 297.0;

    let last = null;

    function clientToElem(e, el) {
        const rect = el.getBoundingClientRect();
        return { x: e.clientX - rect.left, y: e.clientY - rect.top, w: rect.width, h: rect.height };
    }

    clickCatcher.addEventListener('click', function(e){
        const pos = clientToElem(e, previewWrap);
        // convert px coords to percent relative to preview size
        const px = pos.x;
        const py = pos.y;
        const pctX = (px / pos.w) * 100;
        const pctY = (py / pos.h) * 100;
        // convert percent -> mm relative to A4 size
        const mmX = (pctX / 100) * a4WidthMm;
        const mmY = (pctY / 100) * a4HeightMm;
        last = { x_mm: mmX, y_mm: mmY };
        selectedEl.textContent = `x: ${mmX.toFixed(2)} mm, y: ${mmY.toFixed(2)} mm`;
        saveBtn.disabled = false;
        status.textContent = '';
    });

    saveBtn.addEventListener('click', function(){
        if (!last) return;
        saveBtn.disabled = true;
        status.textContent = 'Saving...';
        fetch('{{ route('letterhead.save_offset') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ x: last.x_mm, y: last.y_mm, unit: 'mm' })
        }).then(r => r.json()).then(j => {
            if (j && j.success) {
                status.textContent = 'Saved.';
            } else {
                status.textContent = 'Failed to save';
                console.warn(j);
            }
        }).catch(err => {
            status.textContent = 'Error saving';
            console.error(err);
        }).finally(()=>{ saveBtn.disabled = false; });
    });

    clearBtn.addEventListener('click', function(){
        fetch('{{ route('letterhead.save_offset') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ x: 0, y: 0, unit: 'mm' })
        }).then(r => r.json()).then(j => {
            if (j && j.success) {
                status.textContent = 'Cleared.';
                selectedEl.textContent = '(none)';
            } else {
                status.textContent = 'Failed to clear';
            }
        }).catch(err => {
            status.textContent = 'Error clearing';
        });
    });
})();
</script>
</body>
</html>
