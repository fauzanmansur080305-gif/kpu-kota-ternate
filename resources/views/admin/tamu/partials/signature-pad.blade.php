<style>
    .admin-signature-canvas { display:block; width:100%; height:130px; border:1px dashed #c8d2de; border-radius:10px; background:#fff; touch-action:none; cursor:crosshair; }
    .admin-signature-hint { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:7px; color:#8290a3; font-size:10px; }
    .admin-signature-clear { padding:6px 9px; border:1px solid #e5ebf2; border-radius:8px; color:#65401f; background:white; font-size:10px; font-weight:750; cursor:pointer; }
</style>

<div class="field full">
    <label for="{{ $signaturePrefix }}SignatureCanvas">Paraf</label>
    <canvas class="admin-signature-canvas" id="{{ $signaturePrefix }}SignatureCanvas" data-input-id="{{ $signaturePrefix }}Paraf" aria-label="Area menggambar paraf"></canvas>
    <input id="{{ $signaturePrefix }}Paraf" name="paraf" type="hidden" value="{{ old('paraf', $signatureValue) }}">
    <div class="admin-signature-hint">
        <span>Gambar paraf dengan mouse atau layar sentuh.</span>
        <button class="admin-signature-clear" type="button" data-clear-signature="{{ $signaturePrefix }}SignatureCanvas">Hapus paraf</button>
    </div>
</div>

<script>
    document.querySelectorAll('.admin-signature-canvas').forEach(function (canvas) {
        const input = document.getElementById(canvas.dataset.inputId);
        const context = canvas.getContext('2d');
        let drawing = false;

        function resize() {
            const bounds = canvas.getBoundingClientRect();
            const ratio = window.devicePixelRatio || 1;
            canvas.width = Math.max(1, Math.round(bounds.width * ratio));
            canvas.height = Math.max(1, Math.round(bounds.height * ratio));
            context.setTransform(ratio, 0, 0, ratio, 0, 0);
            context.lineWidth = 2.5;
            context.lineCap = 'round';
            context.lineJoin = 'round';
            context.strokeStyle = '#172b4d';

            if (input.value) {
                const image = new Image();
                image.onload = function () {
                    const width = image.naturalWidth;
                    const height = image.naturalHeight;
                    const scale = Math.min(bounds.width / width, bounds.height / height);
                    const drawWidth = width * scale;
                    const drawHeight = height * scale;
                    context.drawImage(image, (bounds.width - drawWidth) / 2, (bounds.height - drawHeight) / 2, drawWidth, drawHeight);
                };
                image.src = input.value;
            }
        }

        canvas.addEventListener('pointerdown', function (event) {
            drawing = true;
            canvas.setPointerCapture(event.pointerId);
            const bounds = canvas.getBoundingClientRect();
            context.beginPath();
            context.moveTo(event.clientX - bounds.left, event.clientY - bounds.top);
        });
        canvas.addEventListener('pointermove', function (event) {
            if (!drawing) {
                return;
            }

            const bounds = canvas.getBoundingClientRect();
            context.lineTo(event.clientX - bounds.left, event.clientY - bounds.top);
            context.stroke();
        });

        function stopDrawing() {
            if (drawing) {
                drawing = false;
                input.value = canvas.toDataURL('image/png');
            }
        }

        canvas.addEventListener('pointerup', stopDrawing);
        canvas.addEventListener('pointercancel', stopDrawing);
        document.querySelector('[data-clear-signature="' + canvas.id + '"]').addEventListener('click', function () {
            context.clearRect(0, 0, canvas.width, canvas.height);
            input.value = '';
        });
        window.addEventListener('resize', resize);
        resize();
    });
</script>
