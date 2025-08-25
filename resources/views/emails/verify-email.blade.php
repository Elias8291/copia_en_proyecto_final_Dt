<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cuenta</title>
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
        <div class="bg-primary text-white px-6 py-8 text-center">
            <h1 class="text-xl font-semibold mb-2">Padrón de Proveedores</h1>
            <p class="text-sm opacity-90">Secretaría de Administración</p>
            <p class="text-xs opacity-75 mt-1">Gobierno del Estado de Oaxaca</p>
        </div>
        <div class="px-6 py-8">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-semibold text-primary mb-2">¡Bienvenido {{ $user->nombre }}!</h2>
                <p class="text-gray-600 text-sm leading-relaxed">
                    Gracias por registrarte. Para activar tu cuenta, verifica tu correo electrónico.
                </p>
            </div>
            <div class="text-center mb-8">
                <a href="{{ $verificationUrl }}" 
                   class="inline-block bg-primary hover:bg-primary-dark text-white font-medium px-8 py-3 rounded-md transition-colors duration-200 shadow-sm">
                    Verificar cuenta
                </a>
            </div>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-6">
                <h3 class="text-gray-800 font-medium text-center mb-4">Información</h3>
                
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                        <p class="text-xs text-gray-600">Activo por<br><span class="font-semibold text-primary">{{ $expirationHours }}h</span></p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                        <p class="text-xs text-gray-600">Un clic<br><span class="font-semibold text-primary">verifica</span></p>
                    </div>
                    
                    <div class="bg-white border border-gray-200 rounded-md p-3 text-center">
                        <p class="text-xs text-gray-600">Acceso<br><span class="font-semibold text-primary">completo</span></p>
                    </div>
                </div>

                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-md">
                    <div class="text-center">
                        <p class="text-yellow-800 font-medium text-sm mb-1">Importante</p>
                        <p class="text-yellow-700 text-xs leading-relaxed">
                            Verifica en las próximas {{ $expirationHours }} horas o deberás registrarte nuevamente.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mb-6">
                <p class="text-gray-700 text-sm mb-2">Si el botón no funciona, copia este enlace:</p>
                <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                    <code class="text-xs text-gray-600 break-all">{{ $verificationUrl }}</code>
                </div>
            </div>

            <p class="text-gray-500 text-xs italic text-center">
                Si no solicitaste este registro, ignora este mensaje.
            </p>
        </div>  
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