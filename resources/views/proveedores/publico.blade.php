<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Proveedor - {{ $proveedor->razon_social ?? $proveedor->rfc }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .card-shadow {
            box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .primary-red {
            color: #9D2449;
        }
        
        .primary-red-bg {
            background-color: #9D2449;
        }
        
        .primary-red-light {
            color: #B91C5C;
        }
        
        .primary-red-lighter {
            color: #DC2626;
        }
        
        .primary-red-bg-light {
            background-color: #FEE2E2;
        }
        
        .primary-red-bg-lighter {
            background-color: #FEF2F2;
        }
        
        
        body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('{{ asset("images/logoNegro.png") }}');
    background-repeat: repeat;
    background-size: 150px 150px;
    opacity: 0.09;
    filter: blur(0.2px); 
    z-index: -1;
    pointer-events: none;
}

        
    </style>
</head>
<body class="min-h-screen bg-white">
    <div class="min-h-screen">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-8">
            
            <!-- Header Elegante -->
            <div class="bg-white rounded-xl shadow-lg border border-red-100 mb-6 max-w-4xl mx-auto overflow-hidden relative">
                <!-- Decoración de fondo -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-red-100 to-red-200 rounded-full -translate-y-10 translate-x-10 opacity-50"></div>
                <div class="absolute bottom-0 left-0 w-16 h-16 bg-gradient-to-tr from-red-50 to-red-100 rounded-full translate-y-8 -translate-x-8 opacity-50"></div>
                
                <div class="primary-red-bg px-4 py-4 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center justify-center text-center">
                            <div class="space-y-2">
                                <div class="w-12 h-12 bg-white/90 rounded-xl flex items-center justify-center shadow-lg mx-auto backdrop-blur-sm">
                                    <svg class="w-6 h-6 primary-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-xl font-bold text-white mb-1">Proveedor #{{ $proveedor->pv_numero ?? $proveedor->id }}</h1>
                                    <p class="text-base text-red-100 font-medium">{{ $proveedor->razon_social ?? $proveedor->rfc }}</p>
                                </div>
                                <div class="inline-flex items-center px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold text-white border border-white/30">
                                    <svg class="w-3 h-3 mr-1 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Información Pública
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Validación -->
                <div class="p-4 bg-red-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs primary-red">Validado el</p>
                                <p class="text-sm font-semibold primary-red">{{ now()->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs primary-red">Estado</p>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                {{ $proveedor->estado_padron === 'Activo' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($proveedor->estado_padron === 'Pendiente' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                {{ $proveedor->estado_padron ?? 'No especificado' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Datos del Proveedor -->
        <div class="bg-white rounded-xl shadow-lg border border-red-100 mb-6 max-w-4xl mx-auto overflow-hidden relative">
            <!-- Decoración de fondo -->
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full -translate-y-8 translate-x-8 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-12 h-12 bg-gradient-to-tr from-slate-50 to-slate-100 rounded-full translate-y-6 -translate-x-6 opacity-50"></div>
            
            <!-- Header de Datos -->
            <div class="bg-slate-100 px-4 py-3 border-b border-red-200 relative z-10">
                <div class="flex items-center">
                    <div class="w-8 h-8 primary-red-bg-light rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 primary-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">Datos del Proveedor</h2>
                        <p class="text-slate-600 text-sm">Información oficial registrada</p>
                    </div>
                </div>
            </div>

            <!-- Contenido de Datos -->
            <div class="p-4 bg-white relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">Razón Social</label>
                            <p class="text-sm font-semibold text-slate-800">{{ $proveedor->razon_social ?? 'No especificada' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">RFC</label>
                            <p class="text-sm font-mono font-semibold text-slate-800">{{ $proveedor->rfc }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">Número de Proveedor</label>
                            <p class="text-sm font-bold primary-red">{{ $proveedor->pv_numero ?? 'Pendiente' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">Tipo de Persona</label>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $proveedor->tipo_persona === 'Moral' ? 'bg-purple-100 text-purple-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $proveedor->tipo_persona }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">Estado en Padrón</label>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                {{ $proveedor->estado_padron === 'Activo' ? 'bg-emerald-100 text-emerald-700' : 
                                   ($proveedor->estado_padron === 'Pendiente' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                {{ $proveedor->estado_padron ?? 'No especificado' }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">Fecha de Registro</label>
                            <p class="text-sm text-slate-700">{{ $proveedor->fecha_registro ? $proveedor->fecha_registro->format('d/m/Y') : 'No especificada' }}</p>
                        </div>
                        
                        @if($proveedor->fecha_vencimiento_padron)
                        <div>
                            <label class="block text-xs font-semibold primary-red uppercase tracking-wide mb-1">Vigencia hasta</label>
                            <p class="text-sm text-slate-700">{{ $proveedor->fecha_vencimiento_padron->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Domicilio -->
        @if($direcciones->count() > 0)
        <div class="bg-white rounded-xl shadow-lg border border-red-100 mb-6 max-w-4xl mx-auto overflow-hidden relative">
            <!-- Decoración de fondo -->
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full -translate-y-8 translate-x-8 opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-12 h-12 bg-gradient-to-tr from-slate-50 to-slate-100 rounded-full translate-y-6 -translate-x-6 opacity-50"></div>
            
            <!-- Header Domicilio -->
            <div class="bg-slate-100 px-4 py-3 border-b border-red-200 relative z-10">
                <div class="flex items-center">
                    <div class="w-8 h-8 primary-red-bg-light rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 primary-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Domicilio</h3>
                        <p class="text-slate-600 text-sm">Ubicación registrada</p>
                    </div>
                </div>
            </div>
            
            <!-- Contenido Domicilio -->
            <div class="p-4 bg-white relative z-10">
                <div class="space-y-3">
                    @foreach($direcciones as $direccion)
                    <div class="border-l-4 border-[#9D2449] pl-3 bg-red-50 rounded-r-lg p-3 shadow-sm">
                        <p class="font-semibold text-slate-800 text-sm mb-1">
                            {{ $direccion->calle }} {{ $direccion->numero_exterior }}
                            @if($direccion->numero_interior)
                                Int. {{ $direccion->numero_interior }}
                            @endif
                        </p>
                        <p class="text-slate-600 text-sm mb-1">
                            {{ $direccion->colonia_asentamiento }}, {{ $direccion->municipio }}
                        </p>
                        <p class="text-slate-500 text-sm">
                            {{ $direccion->estado->nombre ?? '' }} C.P. {{ $direccion->codigo_postal }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Footer Elegante -->
        <div class="mt-6">
            <div class="bg-white rounded-xl shadow-lg border border-red-100 max-w-4xl mx-auto overflow-hidden relative">
                <!-- Decoración de fondo -->
                <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full -translate-y-8 translate-x-8 opacity-50"></div>
                <div class="absolute bottom-0 left-0 w-12 h-12 bg-gradient-to-tr from-slate-50 to-slate-100 rounded-full translate-y-6 -translate-x-6 opacity-50"></div>
                
                <div class="bg-slate-100 px-4 py-3 border-b border-red-200 relative z-10">
                    <div class="text-center">
                        <div class="w-8 h-8 primary-red-bg rounded-lg flex items-center justify-center shadow-sm mx-auto mb-2">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-800 mb-1">Información Oficial</h3>
                        <p class="text-sm text-slate-600">Esta información es de carácter público y oficial</p>
                    </div>
                </div>
                <div class="p-4 text-center bg-white relative z-10">
                    <p class="text-xs text-slate-500">Sistema de Gestión de Proveedores - {{ now()->format('Y') }}</p>
                    <p class="text-xs text-slate-400 mt-1">Validado el {{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
    </div>


</body>
</html> 