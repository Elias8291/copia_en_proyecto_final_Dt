# Extractor SAT/QR - JavaScript

Esta carpeta contiene todos los archivos JavaScript relacionados con la extracción de códigos QR y scraping de datos del SAT.

## Archivos

### 📄 `qr-extractor-simple.js`
- **Función**: Extrae códigos QR de archivos PDF
- **Tecnologías**: PDF.js, jsQR
- **Clase**: `SimpleQRExtractor`

### 📄 `sat-scraper-simple.js`
- **Función**: Hace scraping de datos del SAT desde URLs
- **Tecnologías**: Fetch API
- **Clase**: `SimpleSATScraper`

### 📄 `constancia-extractor.js`
- **Función**: Orquestador principal que coordina la extracción
- **Tecnologías**: Integra los otros dos módulos
- **Clase**: `ConstanciaExtractor`

## Uso

```javascript
// Crear instancia del extractor
const extractor = new ConstanciaExtractor();

// Extraer datos de un archivo PDF
const result = await extractor.extract(pdfFile);

if (result.success) {
    console.log('Datos extraídos:', result.sat_data);
} else {
    console.error('Error:', result.error);
}
```

## Dependencias

- **PDF.js**: Para procesar archivos PDF
- **jsQR**: Para detectar códigos QR
- **Fetch API**: Para hacer requests al servidor

## Estructura de Datos

```javascript
{
    success: true,
    qr_url: "https://siat.sat.gob.mx/...",
    sat_data: {
        rfc: "XAXX010101000",
        nombre: "RAZÓN SOCIAL",
        tipo_persona: "Moral",
        curp: "XAXX010101HDFXXX01",
        regimen_fiscal: "General de Ley Personas Morales",
        estatus: "ACTIVO",
        entidad_federativa: "CIUDAD DE MÉXICO",
        municipio: "CUAUHTÉMOC",
        cp: "06000",
        colonia: "CENTRO",
        nombre_vialidad: "AVENIDA JUÁREZ",
        numero_exterior: "123",
        numero_interior: ""
    }
}
``` 