<?php
/**
 * Script de prueba para verificar la API de extracción de QR
 */

// Simular una petición POST a la API
$url = 'http://localhost:8000/api/extract-qr-url';

// Crear un archivo PDF de prueba (simulado)
$pdfContent = '%PDF-1.4
1 0 obj
<<
/Type /Catalog
/Pages 2 0 R
>>
endobj

2 0 obj
<<
/Type /Pages
/Kids [3 0 R]
/Count 1
>>
endobj

3 0 obj
<<
/Type /Page
/Parent 2 0 R
/MediaBox [0 0 612 792]
/Contents 4 0 R
>>
endobj

4 0 obj
<<
/Length 44
>>
stream
BT
/F1 12 Tf
72 720 Td
(Test PDF) Tj
ET
endstream
endobj

xref
0 5
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
0000000204 00000 n 
trailer
<<
/Size 5
/Root 1 0 R
>>
startxref
364
%%EOF';

// Crear archivo temporal
$tempFile = tempnam(sys_get_temp_dir(), 'test_pdf_');
file_put_contents($tempFile, $pdfContent);

// Preparar datos para la petición
$postData = [
    'pdf' => new CURLFile($tempFile, 'application/pdf', 'test.pdf')
];

// Configurar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: multipart/form-data'
]);

// Ejecutar petición
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Limpiar archivo temporal
unlink($tempFile);

// Mostrar resultados
echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if (curl_error($ch)) {
    echo "cURL Error: " . curl_error($ch) . "\n";
}

curl_close($ch); 