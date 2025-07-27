# 🚀 Extractor de QR del SAT - Versión JavaScript

Sistema simple y funcional para extraer códigos QR de PDFs del SAT usando solo JavaScript.

## ✨ Características

- ✅ **Solo JavaScript**: No requiere Python ni dependencias del servidor
- ✅ **Procesamiento en el navegador**: PDF.js + jsQR
- ✅ **Alta resolución**: Escala 3x para mejor detección
- ✅ **Múltiples escalas**: Fallback automático si no detecta QR
- ✅ **Validación SAT**: Solo acepta URLs del SAT oficial
- ✅ **Scraping del SAT**: Obtiene datos fiscales automáticamente
- ✅ **Limpio y funcional**: Código simple y fácil de entender

## 📁 Archivos Principales

### JavaScript
- `public/js/qr-extractor-simple.js` - Extractor de QR puro
- `public/js/sat-scraper-simple.js` - Scraper del SAT
- `public/js/constancia-extractor.js` - Orquestador principal

### Ejemplo
- `public/js/example-usage.html` - Ejemplo de uso completo

## 🔧 Instalación

### 1. Incluir las librerías en tu HTML

```html
<!-- PDF.js para procesar PDFs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js"></script>

<!-- jsQR para detectar códigos QR -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<!-- Nuestros scripts -->
<script src="/js/qr-extractor-simple.js"></script>
<script src="/js/sat-scraper-simple.js"></script>
<script src="/js/constancia-extractor.js"></script>
```

### 2. CSRF Token

Asegúrate de tener el token CSRF en tu HTML:

```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## 🚀 Uso

### Uso Básico

```javascript
// Crear extractor
const extractor = new ConstanciaExtractor();

// Procesar archivo PDF
const file = document.getElementById('fileInput').files[0];
const result = await extractor.extract(file);

if (result.success) {
    console.log('URL del QR:', result.qr_url);
    console.log('Datos del SAT:', result.sat_data);
} else {
    console.error('Error:', result.error);
}
```

### Uso con Callbacks

```javascript
const result = await extractor.extractWithCallbacks(file, {
    onStart: () => console.log('Iniciando...'),
    onProgress: (message) => console.log('Progreso:', message),
    onSuccess: (satData, qrUrl) => {
        console.log('Éxito!', satData, qrUrl);
        // Llenar formulario con datos
        fillForm(satData);
    },
    onError: (error) => console.error('Error:', error),
    onFinish: () => console.log('Completado')
});
```

## 📊 Datos Extraídos

El sistema extrae y normaliza los siguientes datos:

```javascript
{
    rfc: "XAXX010101000",
    nombre: "RAZÓN SOCIAL O NOMBRE COMPLETO",
    curp: "CURP123456789",
    regimen_fiscal: "Régimen General de Ley",
    estatus: "ACTIVO",
    entidad_federativa: "CIUDAD DE MÉXICO",
    municipio: "CUAUHTÉMOC",
    email: "correo@ejemplo.com",
    tipo_persona: "moral", // o "fisica"
    cp: "06000",
    colonia: "COLONIA",
    nombre_vialidad: "CALLE",
    numero_exterior: "123",
    numero_interior: "A"
}
```

## 🔍 Proceso

1. **Usuario sube PDF** → JavaScript procesa el archivo
2. **PDF.js renderiza** → Convierte PDF a imagen de alta resolución
3. **jsQR detecta** → Busca códigos QR en la imagen
4. **Valida URL** → Verifica que sea del SAT oficial
5. **Scraping SAT** → Obtiene datos fiscales del servidor
6. **Normaliza datos** → Formatea para uso en formularios

## 🛠️ Personalización

### Cambiar escala de renderizado

```javascript
// En qr-extractor-simple.js, línea 35
const viewport = page.getViewport({ scale: 3.0 }); // Cambiar escala
```

### Agregar más escalas de detección

```javascript
// En qr-extractor-simple.js, línea 120
const scales = [0.5, 0.75, 1.25, 1.5, 2.0]; // Agregar más escalas
```

### Personalizar validación de URL

```javascript
// En qr-extractor-simple.js, línea 50
if (qrCode.includes('siat.sat.gob.mx')) {
    // Validación personalizada aquí
}
```

## 🐛 Solución de Problemas

### Error: "PDF.js no está disponible"
- Verifica que las librerías estén cargadas
- Revisa la consola del navegador

### Error: "jsQR no está disponible"
- Asegúrate de incluir el script de jsQR
- Verifica la versión de la librería

### No detecta códigos QR
- Aumenta la escala de renderizado
- Verifica que el PDF tenga QR visible
- Revisa la calidad del PDF

### Error de CSRF
- Verifica que el token CSRF esté en el HTML
- Revisa las rutas en el middleware

## 📝 Notas

- ✅ **Solo JavaScript**: No requiere Python ni dependencias del servidor
- ✅ **Procesamiento local**: Todo se hace en el navegador
- ✅ **Alta seguridad**: Validación de URLs del SAT
- ✅ **Fácil mantenimiento**: Código simple y claro
- ✅ **Escalable**: Fácil de personalizar y extender

## 🎯 Ejemplo Completo

Ver `public/js/example-usage.html` para un ejemplo completo de uso.

---

**¡Listo!** El sistema ahora es completamente JavaScript, limpio y funcional. 🚀 