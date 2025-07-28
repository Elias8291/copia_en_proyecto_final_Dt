# Solución: Cálculo de Tipo de Persona basado en RFC

## Problema
En la vista `revision-digital.blade.php` no se estaba pasando correctamente el tipo de persona. El sistema necesitaba calcular automáticamente el tipo de persona basado en el RFC del proveedor asociado al trámite.

## Reglas de Negocio
- **RFC de 13 caracteres** = Persona Física
- **RFC de 12 caracteres** = Persona Moral

## Solución Implementada

### 1. Helper TiempoHelper.php
Se agregaron métodos para calcular el tipo de persona:

```php
/**
 * Calcula el tipo de persona basado en el RFC
 * RFC de 13 caracteres = Persona Física
 * RFC de 12 caracteres = Persona Moral
 */
public static function calcularTipoPersonaPorRfc(?string $rfc): ?string
{
    if (!$rfc) {
        return null;
    }

    $rfc = strtoupper(trim($rfc));
    
    // Validar formato básico del RFC
    if (!preg_match('/^[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}$/', $rfc)) {
        return null;
    }

    // Determinar tipo de persona por longitud
    return match(strlen($rfc)) {
        12 => 'Moral',
        13 => 'Física',
        default => null
    };
}

/**
 * Obtiene el tipo de persona del proveedor, calculándolo si es necesario
 */
public static function getTipoPersona($proveedor): ?string
{
    if (!$proveedor) {
        return null;
    }

    // Si ya tiene tipo_persona asignado, usarlo
    if ($proveedor->tipo_persona) {
        return $proveedor->tipo_persona;
    }

    // Si no tiene tipo_persona, calcularlo basado en el RFC
    return self::calcularTipoPersonaPorRfc($proveedor->rfc);
}
```

### 2. ProveedorService.php
Se actualizó el servicio para usar el helper:

```php
/**
 * Calcula el tipo de persona basado en el RFC del proveedor
 */
public function calcularTipoPersonaPorRfc(?Proveedor $proveedor): ?string
{
    if (!$proveedor || !$proveedor->rfc) {
        return null;
    }

    return TiempoHelper::calcularTipoPersonaPorRfc($proveedor->rfc);
}

/**
 * Obtiene el tipo de persona del proveedor, calculándolo si es necesario
 */
public function getTipoPersona(?Proveedor $proveedor): ?string
{
    return TiempoHelper::getTipoPersona($proveedor);
}
```

### 3. RevisionController.php
Se modificó el controlador para calcular y actualizar el tipo de persona:

```php
public function revisarDatos(Tramite $tramite)
{
    try {
        $tramite->load([
            'proveedor.user',
            'revisadoPor',
            'datosGenerales',
            'datosConstitutivos',
            'apoderadoLegal',
            'contactos',
            'accionistas',
            'direcciones.estado',
            'actividades',
            'archivos.catalogoArchivo'
        ]);

        // Calcular el tipo de persona basado en el RFC del proveedor
        if ($tramite->proveedor) {
            $tipoPersona = $this->proveedorService->getTipoPersona($tramite->proveedor);
            
            // Si el proveedor no tiene tipo_persona asignado, actualizarlo
            if (!$tramite->proveedor->tipo_persona && $tipoPersona) {
                $tramite->proveedor->update(['tipo_persona' => $tipoPersona]);
                $tramite->load('proveedor'); // Recargar la relación
            }
        }

        return view('revision.revision-digital', compact('tramite'));
    } catch (\Exception $e) {
        Log::error('Error al cargar datos del trámite para revisión', [
            'tramite_id' => $tramite->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()->with('error', 'Error al cargar los datos del trámite: ' . $e->getMessage());
    }
}
```

### 4. Vista datos-generales.blade.php
Se actualizó la vista para usar el helper:

```php
@props(['tramite', 'proveedor', 'editable' => false])

@php
    use App\Helpers\TiempoHelper;
    $tipoPersona = TiempoHelper::getTipoPersona($proveedor);
@endphp

<!-- ... -->

<div class="form-group field-container">
    <label class="block text-sm font-medium text-gray-700 mb-2 field-label">
        Tipo de Persona
    </label>
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <i class="fas fa-user-tag text-gray-500"></i>
        </div>
        <div class="block w-full pl-10 pr-4 py-2.5 text-gray-700 bg-gray-50 border border-gray-200 rounded-lg shadow-sm">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoPersona === 'Moral' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                {{ $tipoPersona ?? 'N/A' }}
            </span>
        </div>
    </div>
</div>

@if($tipoPersona === 'Física')
    <!-- Mostrar campo CURP solo para personas físicas -->
@endif
```

## Validación
Se creó y ejecutó un test que confirma que el cálculo funciona correctamente:

- ✅ RFC de 12 caracteres = Persona Moral
- ✅ RFC de 13 caracteres = Persona Física
- ✅ RFC inválidos = null
- ✅ RFC vacío = null

## Beneficios
1. **Automatización**: El tipo de persona se calcula automáticamente basado en el RFC
2. **Consistencia**: Se usa la misma lógica en toda la aplicación
3. **Mantenibilidad**: La lógica está centralizada en el helper
4. **Validación**: Se valida el formato del RFC antes de calcular el tipo
5. **Flexibilidad**: Si el proveedor ya tiene tipo_persona asignado, se usa ese valor

## Archivos Modificados
- `app/Helpers/TiempoHelper.php` - Agregados métodos de cálculo
- `app/Services/ProveedorService.php` - Actualizado para usar el helper
- `app/Http/Controllers/RevisionController.php` - Agregada lógica de cálculo y actualización
- `resources/views/revision/partials/datos-generales.blade.php` - Actualizada para usar el helper 