<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Reagendada</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9d2449',
                        'primary-dark': '#8a203f',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 font-sans text-gray-800 m-0 p-5">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="bg-primary text-white px-6 py-8 text-center">
            <h1 class="text-xl font-semibold mb-2">Padrón de Proveedores</h1>
            <p class="text-sm opacity-90">Secretaría de Administración</p>
            <p class="text-xs opacity-75 mt-1">Gobierno del Estado de Oaxaca</p>
        </div>
        
        <!-- Content -->
        <div class="px-6 py-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-semibold text-primary mb-2">Cita Reagendada</h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Estimado/a {{ $usuario->nombre }}, su cita ha sido reagendada automáticamente.
                </p>
            </div>

            <!-- Alert Section -->
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-md mb-6">
                <div class="text-center">
                    <p class="text-blue-800 font-medium text-sm mb-1">Nueva Cita Programada</p>
                    <p class="text-blue-700 text-xs leading-relaxed">
                        Folio #{{ $tramite->id }} - Estado: {{ $tramite->estado }}
                    </p>
                </div>
            </div>

            <!-- Info Section -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-gray-800 font-medium text-center mb-4">Detalles del Trámite</h3>
                
                <div class="space-y-3">
                    <div class="bg-white border border-gray-200 rounded-md p-3">
                        <p class="text-xs text-gray-600">Tipo de Trámite</p>
                        <p class="font-semibold text-primary text-sm">{{ $tramite->tipo_tramite }}</p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-md p-3">
                        <p class="text-xs text-gray-600">Fecha de Inicio</p>
                        <p class="font-semibold text-primary text-sm">{{ $tramite->fecha_inicio ? $tramite->fecha_inicio->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    
                    @if($tramite->cita)
                    <div class="bg-white border border-gray-200 rounded-md p-3">
                        <p class="text-xs text-gray-600">Nueva Fecha de Cita</p>
                        <p class="font-semibold text-primary text-sm">{{ $tramite->cita->fecha_cita->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Reason Section -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <h3 class="text-yellow-800 font-medium text-sm mb-2">Motivo del Reagendamiento</h3>
                <p class="text-yellow-700 text-xs leading-relaxed">
                    Su cita anterior no fue atendida, por lo que se ha reagendado automáticamente.
                </p>
            </div>

            <!-- Action Section -->
            <div class="text-center mb-6">
                <p class="text-gray-700 text-sm mb-3">¿Qué debe hacer ahora?</p>
                <div class="space-y-2">
                    <div class="bg-green-50 border border-green-200 rounded-md p-3">
                        <p class="text-green-800 text-xs font-medium">Asistir a la Nueva Cita</p>
                        <p class="text-green-700 text-xs">Revise la nueva fecha y hora programada</p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                        <p class="text-blue-800 text-xs font-medium">Verificar Estado</p>
                        <p class="text-blue-700 text-xs">Consulte el estado de su trámite en el sistema</p>
                    </div>
                </div>
            </div>

            <p class="text-gray-500 text-xs italic text-center">
                Esta es la primera reagendación automática. Recuerde asistir a la nueva cita.
            </p>
        </div>

        <!-- Footer -->
        <div class="bg-gray-50 border-t border-gray-200 px-6 py-6 text-center">
            <h3 class="text-gray-800 font-semibold text-sm mb-2">Padrón de Proveedores</h3>
            <p class="text-gray-600 text-xs mb-1">Secretaría de Administración</p>
            <p class="text-gray-500 text-xs mb-4">Gobierno del Estado de Oaxaca</p>
            
            <div class="w-8 h-px bg-gray-300 mx-auto mb-4"></div>
            
            <p class="text-gray-500 text-xs mb-2">Correo automático. No responda a este mensaje.</p>
            <p class="text-gray-400 text-xs">© {{ date('Y') }} Gobierno del Estado de Oaxaca.</p>
        </div>
    </div>
</body>
</html> 