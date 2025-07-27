<?php
/**
 * Script de prueba para la ruta web sin CSRF
 */

$url = 'http://localhost:8000/extract-qr-url-web';

// Crear un archivo PDF simple
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
>>
endobj

xref
0 4
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
trailer
<<
/Size 4
/Root 1 0 R
>>
startxref
200
%%EOF';

$tempFile = tempnam(sys_get_temp_dir(), 'test_');
file_put_contents($tempFile, $pdfContent);

$postData = [
    'pdf' => new CURLFile($tempFile, 'application/pdf', 'test.pdf')
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

unlink($tempFile);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n";

if (curl_error($ch)) {
    echo "cURL Error: " . curl_error($ch) . "\n";
}

curl_close($ch); 