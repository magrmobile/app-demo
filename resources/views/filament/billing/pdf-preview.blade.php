@if (!empty($pdfFilename))
    <div class="w-full h-[85vh]">
        <iframe width="100%" height="600px"
            src="{{ route('billing.pdf.show', ['filename' => $pdfFilename]) }}#zoom=150&view=FitH"
            class="w-full h-full border rounded"
        ></iframe>
    </div>
@else
    <div class="text-gray-500 text-sm">
        Aún no se ha generado el documento.
    </div>
@endif