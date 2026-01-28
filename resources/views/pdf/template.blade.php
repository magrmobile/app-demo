<!DOCTYPE html>
<html>
<head>
    <title>Plantilla PDF</title>
    <style>
        /* Estilos CSS para el PDF */
    </style>
</head>
<body>
    <h1>Información del JSON</h1>
    <pre>{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
</body>
</html>

