<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .content {
            text-align: justify;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p><strong>Número:</strong> {{ $numero }}</p>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>
    </div>
    
    <div class="content">
        <p>{{ $contenido }}</p>
        <p>Este documento es una prueba para verificar que la generación de PDFs funciona correctamente en el sistema.</p>
    </div>
    
    <div class="footer">
        <p>Documento generado automáticamente por el sistema</p>
    </div>
</body>
</html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .content {
            text-align: justify;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $titulo }}</h1>
        <p><strong>Número:</strong> {{ $numero }}</p>
        <p><strong>Fecha:</strong> {{ $fecha }}</p>
    </div>
    
    <div class="content">
        <p>{{ $contenido }}</p>
        <p>Este documento es una prueba para verificar que la generación de PDFs funciona correctamente en el sistema.</p>
    </div>
    
    <div class="footer">
        <p>Documento generado automáticamente por el sistema</p>
    </div>
</body>
</html> 