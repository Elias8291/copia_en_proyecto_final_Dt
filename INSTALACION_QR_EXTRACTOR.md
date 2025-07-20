# Instalación del Extractor de QR desde PDF

Este sistema permite extraer códigos QR (URLs) desde archivos PDF usando Python.

## Requisitos del Sistema

### Python 3.7+

Asegúrate de tener Python instalado:

```bash
python --version
```

### Dependencias de Python

Instala las librerías necesarias:

```bash
pip install PyMuPDF pyzbar pillow opencv-python
```

O usando requirements.txt:

```bash
pip install -r requirements.txt
```

### Dependencias del Sistema (Linux/Ubuntu)

Si estás en Linux, también necesitas:

```bash
sudo apt-get update
sudo apt-get install libzbar0
```

### Dependencias del Sistema (macOS)

```bash
brew install zbar
```

### Dependencias del Sistema (Windows)

Las dependencias se instalan automáticamente con pip en Windows.

## Uso

### Desde PHP/Laravel

El controlador `QRExtractorController` maneja automáticamente la extracción:

```php
POST /api/extract-qr-pdf
Content-Type: multipart/form-data
Body: pdf (archivo PDF)
```

### Desde línea de comandos

```bash
py app/Python/qr_extractor.py archivo.pdf
```

### Respuesta JSON

```json
{
    "success": true,
    "url": "https://ejemplo.com/qr-url"
}
```

O en caso de error:

```json
{
    "success": false,
    "error": "No se encontró código QR válido en el PDF"
}
```

## Características

-   ✅ Extrae QR codes de cualquier página del PDF
-   ✅ Filtra automáticamente URLs (que empiecen con 'http')
-   ✅ Manejo robusto de errores
-   ✅ Optimizado para velocidad
-   ✅ Compatible con múltiples formatos de QR
-   ✅ Limpieza automática de archivos temporales

## Solución de Problemas

### Error: "No module named 'fitz'"

```bash
pip install PyMuPDF
```

### Error: "No module named 'pyzbar'"

```bash
pip install pyzbar
# En Linux también: sudo apt-get install libzbar0
```

### Error: "No module named 'cv2'"

```bash
pip install opencv-python
```

### El script no encuentra QR codes

-   Verifica que el PDF contenga códigos QR visibles
-   Asegúrate de que el QR contenga una URL (empiece con 'http')
-   Prueba con un PDF de mejor calidad

## Archivos Relacionados

-   `app/Python/qr_extractor.py` - Script principal de Python
-   `app/Http/Controllers/Api/QRExtractorController.php` - Controlador Laravel
-   `routes/api.php` - Rutas de la API (agregar si no existe)

## Ruta de API Sugerida

Agregar en `routes/api.php`:

```php
Route::post('/extract-qr-pdf', [QRExtractorController::class, 'extractQrFromPdf']);
```
