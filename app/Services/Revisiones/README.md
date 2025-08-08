# Servicios de Revisión

Esta carpeta contiene todos los servicios relacionados con el proceso de revisión de trámites.

## Estructura

```
📁 app/Services/Revisiones/
├── 📄 DecisionesFinalesService.php    # Decisiones finales (aprobar/rechazar)
├── 📄 RevisionDigitalService.php      # Lógica específica de revisión digital
├── 📄 RevisionPresencialService.php   # Lógica específica de revisión presencial
├── 📄 RevisionDomiciliariaService.php # Lógica específica de revisión domiciliaria
└── 📄 README.md                       # Esta documentación
```

## Servicios

### DecisionesFinalesService
Maneja las decisiones finales de un trámite:
- **aprobarYAgendarCita()**: Aprueba y agenda cita presencial
- **rechazarParaCorreccion()**: Rechaza y envía para corrección
- **rechazarCompleto()**: Rechaza completamente el trámite

### RevisionDigitalService
Lógica específica para revisión digital de trámites.

### RevisionPresencialService
Lógica específica para revisión presencial de trámites.

### RevisionDomiciliariaService
Lógica específica para revisión domiciliaria de trámites.

## Uso

```php
// En el controlador
use App\Services\Revisiones\DecisionesFinalesService;

public function __construct(DecisionesFinalesService $decisionesService) {
    $this->decisionesService = $decisionesService;
}

// Usar el servicio
$resultado = $this->decisionesService->aprobarYAgendarCita($tramiteId, $comentario);
``` 