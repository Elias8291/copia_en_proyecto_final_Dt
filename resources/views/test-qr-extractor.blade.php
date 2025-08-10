<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test QR Extractor</title>
    <script src="/vendor/pdfjs-dist/pdf.min.js"></script>
    <script src="/vendor/pdfjs-dist/pdf.worker.min.js"></script>
    <script src="/vendor/jsQR/jsQR.min.js"></script>
    <script src="/js/sat-qr-extractor/qr-extractor-simple.js"></script>
    <script src="/js/sat-qr-extractor/sat-scraper-simple.js"></script>
    <script src="/js/sat-qr-extractor/constancia-extractor.js"></script>
    <script src="/js/auth/register-handler.js"></script>
</head>
<body>
    <h1>Test QR Extractor</h1>
    
    <input type="file" id="testFile" accept=".pdf" onchange="testUpload(this)">
    
    <div id="result"></div>
    
    <script>
        async function testUpload(input) {
            const resultDiv = document.getElementById('result');
            resultDiv.innerHTML = 'Procesando...';
            
            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const handler = new RegisterHandler();
                
                try {
                    const result = await handler.processFile(file);
                    resultDiv.innerHTML = `
                        <h3>Resultado:</h3>
                        <pre>${JSON.stringify(result, null, 2)}</pre>
                    `;
                } catch (error) {
                    resultDiv.innerHTML = `
                        <h3>Error:</h3>
                        <pre>${error.message}</pre>
                    `;
                }
            }
        }
        
        // Verificar que las clases estén disponibles
        console.log('SimpleQRExtractor:', typeof SimpleQRExtractor);
        console.log('SimpleSATScraper:', typeof SimpleSATScraper);
        console.log('ConstanciaExtractor:', typeof ConstanciaExtractor);
        console.log('uploadFile:', typeof uploadFile);
    </script>
</body>
</html>
