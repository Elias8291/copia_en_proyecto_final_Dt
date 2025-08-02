# 🛠️ Comandos Útiles para Desarrollo de Trámites

## 🧪 Testing

### Ejecutar tests de servicios específicos
```bash
# Todos los tests
php artisan test

# Tests específicos de servicios de trámites
php artisan test --filter=TramiteService
php artisan test --filter=SesionSatService
php artisan test --filter=ValidacionTramiteService

# Con coverage
php artisan test --coverage
```

## 🔍 Debugging

### Verificar servicios registrados
```bash
# Ver todos los servicios registrados
php artisan tinker
>>> app()->getBindings()
>>> app(App\Services\Tramites\SesionSatService::class)
```

### Limpiar caché de servicios
```bash
php artisan clear-compiled
php artisan config:clear
php artisan cache:clear
```

## 📊 Análisis de Código

### Verificar errores de linting
```bash
# PHPStan (si está instalado)
./vendor/bin/phpstan analyse app/Services/Tramites/

# PHP CS Fixer (si está instalado)
./vendor/bin/php-cs-fixer fix app/Services/Tramites/
```

### Métricas de código
```bash
# Contar líneas de código
find app/Services/Tramites/ -name "*.php" -exec wc -l {} + | tail -1

# Buscar TODOs y FIXMEs
grep -r "TODO\|FIXME" app/Services/Tramites/
```

## 🔧 Comandos de Desarrollo

### Crear nuevos servicios
```bash
# Crear servicio en namespace correcto
php artisan make:class App/Services/Tramites/NuevoService
```

### Verificar rutas que usan servicios
```bash
php artisan route:list | grep -i tramite
```

### Verificar eventos y listeners
```bash
php artisan event:list
```

## 🐛 Debugging Específico

### Verificar datos de sesión SAT
```bash
php artisan tinker
>>> session()->all()
>>> session()->get('sat_rfc')
```

### Probar validaciones
```bash
php artisan tinker
>>> $service = app(App\Services\Tramites\ValidacionTramiteService::class)
>>> $service->normalizarRfc('  abc123def456  ')
>>> $service->esPersonaMoral('ABC123456789')
```

### Probar servicios de formulario
```bash
php artisan tinker
>>> $tramite = App\Models\Tramite::first()
>>> $service = app(App\Services\Tramites\FormularioTramiteService::class)
>>> // Probar métodos específicos
```

## 📝 Logs Útiles

### Ver logs específicos de trámites
```bash
# Logs en tiempo real
tail -f storage/logs/laravel.log | grep -i tramite

# Buscar errores específicos
grep -i "error.*tramite" storage/logs/laravel.log

# Logs de hoy
grep "$(date +%Y-%m-%d)" storage/logs/laravel.log | grep -i tramite
```

## 🔄 Comandos de Mantenimiento

### Limpiar datos de prueba
```bash
php artisan tinker
>>> App\Models\Tramite::where('tipo_tramite', 'like', '%test%')->delete()
```

### Verificar integridad de datos
```bash
php artisan tinker
>>> App\Models\Tramite::whereNull('proveedor_id')->count()
>>> App\Models\Proveedor::whereNull('rfc')->count()
```

## 🚀 Comandos de Producción

### Optimizar servicios para producción
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Verificar performance
```bash
php artisan tinker
>>> $start = microtime(true)
>>> app(App\Services\Tramites\ValidacionTramiteService::class)->normalizarRfc('test')
>>> echo (microtime(true) - $start) * 1000 . " ms"
```

## 📋 Checklist de Desarrollo

### Antes de hacer commit
- [ ] Tests pasan: `php artisan test`
- [ ] No hay errores de linting
- [ ] Documentación actualizada
- [ ] Logs de debug removidos
- [ ] Variables de entorno verificadas

### Antes de deploy
- [ ] Cache optimizado
- [ ] Configuración verificada
- [ ] Servicios registrados correctamente
- [ ] Tests de integración pasan
- [ ] Backup de base de datos

## 🔍 Comandos de Investigación

### Buscar uso de métodos específicos
```bash
# Buscar dónde se usa un servicio
grep -r "SesionSatService" app/
grep -r "validarRfcConstancia" app/

# Buscar patrones específicos
grep -r "procesarDatos" app/Services/Tramites/
```

### Analizar dependencias
```bash
# Ver qué servicios dependen de otros
grep -r "private.*Service" app/Services/Tramites/
```

## 💡 Tips de Desarrollo

### Usar servicios en Tinker
```bash
php artisan tinker
>>> $sesion = app(\App\Services\Tramites\SesionSatService::class)
>>> $validacion = app(\App\Services\Tramites\ValidacionTramiteService::class)
>>> $respuesta = app(\App\Services\Tramites\RespuestaHttpService::class)
```

### Verificar inyección de dependencias
```bash
php artisan tinker
>>> app()->make(\App\Http\Controllers\RevisionController::class)
>>> // Verificar que se inyectan correctamente todos los servicios
```

### Probar respuestas HTTP
```bash
php artisan tinker
>>> $request = new \Illuminate\Http\Request()
>>> $request->headers->set('Accept', 'application/json')
>>> // Simular peticiones AJAX
```