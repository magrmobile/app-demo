<div id="pdfModal" class="modal">
    <div class="modal-content">
        <iframe id="pdfViewer" srcdoc="{{ $pdfContent }}"></iframe>
    </div>
</div>

<script>
    // Abrir el modal al cargar la pagina
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('pdfModal');
        M.Modal.init(modal).open();
    });
</script>