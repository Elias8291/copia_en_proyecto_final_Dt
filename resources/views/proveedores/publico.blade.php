<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información del Proveedor - {{ $proveedor->razon_social ?? $proveedor->rfc }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

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


            <div class="bg-white shadow-lg border border-gray-300 max-w-4xl mx-auto overflow-hidden">

                <div class="bg-white border-b-4 border-[#B91C1C] py-4 px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ asset('images/logoColor.png') }}" alt="Logo" class="h-12 w-auto">
                        </div>
                        <div class="text-right">
                            <h2 class="text-lg font-semibold text-black">Información del Proveedor</h2>
                        </div>
                    </div>
                </div>

                <div class="p-8 bg-gray-50">

                    <div class="bg-white border border-gray-300 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="md:col-span-2">
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                                    </svg>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Razón Social / Nombre</label>
                                </div>
                                <p class="text-base font-bold text-gray-900">{{ $proveedor->razon_social ?? $proveedor->rfc }}</p>
                            </div>

                            <div class="text-center">
                                <div class="flex items-center justify-center mb-2">
                                    <svg class="w-4 h-4 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Número PV</label>
                                </div>
                                <p class="text-3xl font-bold text-[#B91C1C]">{{ $proveedor->pv_numero ?? '001' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">RFC</label>
                                </div>
                                <p class="text-lg font-mono font-bold text-gray-900 bg-gray-100 px-3 py-2 rounded">{{ $proveedor->rfc }}</p>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Tipo de Persona</label>
                                </div>
                                <span class="inline-block px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg">
                                    {{ $proveedor->tipo_persona ?? 'Física' }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                    </svg>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Estado en Padrón</label>
                                </div>
                                <span class="inline-block px-4 py-2 text-sm font-bold text-white bg-green-600 rounded-lg">
                                    {{ $proveedor->estado_padron ?? 'Activo' }}
                                </span>
                            </div>

                            <div>
                                <div class="flex items-center mb-2">
                                    <svg class="w-4 h-4 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zM4 9h12v8H4V9z" />
                                    </svg>
                                    <label class="text-xs font-semibold text-gray-700 uppercase tracking-wide">Vigencia</label>
                                </div>
                                <div class="text-sm text-gray-800">
                                    <p><span class="font-medium">Inicio:</span> {{ $proveedor->fecha_alta_padron ? $proveedor->fecha_alta_padron->format('d/m/Y') : ($proveedor->fecha_registro ? $proveedor->fecha_registro->format('d/m/Y') : 'No especificada') }}</p>
                                    <p><span class="font-medium">Vence:</span> {{ $proveedor->fecha_vencimiento_padron ? $proveedor->fecha_vencimiento_padron->format('d/m/Y') : 'No especificada' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($direcciones->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">Domicilio</h3>
                        @foreach($direcciones as $direccion)
                        <div class="bg-white border border-gray-300 rounded p-3">
                            <p class="text-sm text-gray-800">
                                {{ $direccion->calle }} {{ $direccion->numero_exterior }}@if($direccion->numero_interior), Int. {{ $direccion->numero_interior }}@endif, {{ $direccion->colonia_asentamiento }}, {{ $direccion->municipio }}, {{ $direccion->estado->nombre ?? '' }} C.P. {{ $direccion->codigo_postal }}
                            </p>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if($ultimoTramite && $ultimoTramite->actividades->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-800 mb-3">Actividades Económicas</h3>
                        <div class="space-y-2">
                            @foreach($ultimoTramite->actividades as $actividadProveedor)
                            <div class="bg-white border border-gray-300 rounded p-3">
                                <p class="text-xs text-gray-800">
                                    {{ $actividadProveedor->actividad->descripcion ?? $actividadProveedor->actividad->nombre ?? 'Comercio al por mayor de maquinaria, equipo y mobiliario para actividades agropecuarias, industriales, de servicios y comerciales, y de otra maquinaria y equipo de uso general.' }}
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    <div class="text-center mt-8 pt-4 border-t border-gray-300">
                        <p class="text-xs font-bold text-gray-600">Información Oficial</p>
                        <p class="text-xs text-gray-500">Esta información es de carácter público y oficial</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>