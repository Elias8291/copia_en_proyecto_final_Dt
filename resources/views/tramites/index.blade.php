@extends('layouts.app')

@section('content')
    @push('styles')
        <style>
            .card-disabled {
                position: relative;
                cursor: not-allowed;
            }

            .card-disabled::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(243, 244, 246, 0.7);
                backdrop-filter: blur(2px);
                border-radius: inherit;
                z-index: 10;
            }

            .card-disabled::after {
                content: '🔒';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 2rem;
                z-index: 20;
            }

            .gradient-text {
                background: linear-gradient(135deg, #9d2449 0%, #be185d 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .card-hover {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .card-hover:not(.card-disabled):hover {
                transform: translateY(-4px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .proveedor-info {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                border: 1px solid #cbd5e1;
            }

            .status-badge {
                display: inline-flex;
                align-items: center;
                padding: 0.25rem 0.75rem;
                border-radius: 9999px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
            }

            .status-activo {
                background-color: #dcfce7;
                color: #166534;
                border: 1px solid #bbf7d0;
            }

            .status-pendiente {
                background-color: #fef3c7;
                color: #92400e;
                border: 1px solid #fde68a;
            }

            .status-inactivo {
                background-color: #fee2e2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }
        </style>
    @endpush

    <div class="min-h-screen p-4 sm:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Alertas de sesión -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('warning') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Container -->
            <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <div
                        class="inline-flex items-center gap-2 bg-gray-100 text-primary px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <i class="fas fa-clipboard-list text-sm"></i>
                        <span>Sistema de Trámites</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-bold gradient-text mb-4">
                        Trámites
                    </h1>
                    <div class="w-20 h-1 bg-gradient-to-r from-primary to-pink-600 mx-auto mb-4 rounded-full"></div>

                    @if ($availableProcedures['is_administrative'])
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Como usuario administrativo, puede ver pero no realizar trámites. Esta vista es solo
                                        informativa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <p class="text-base text-gray-600 max-w-xl mx-auto leading-relaxed mb-2">
                        {{ $availableProcedures['message'] }}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <!-- Card 1: Inscripción -->
                    <div
                        class="group bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-200 {{ !$availableProcedures['inscripcion'] || $availableProcedures['is_administrative'] ? 'card-disabled' : '' }}">
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-primary to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-user-plus text-white text-xl"></i>
                            </div>
                            <span
                                class="bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                NUEVO
                            </span>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold mb-3 text-gray-800">Inscripción</h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                Inicie su inscripción al padrón de proveedores. Disponible para personas físicas y morales.
                            </p>

                            <div class="space-y-2 mb-6">
                                <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                    <i class="fas fa-check-circle text-primary text-sm mr-3"></i>
                                    <span class="text-xs text-gray-700">Registro inicial completo</span>
                                </div>
                                <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                    <i class="fas fa-check-circle text-primary text-sm mr-3"></i>
                                    <span class="text-xs text-gray-700">Documentación requerida</span>
                                </div>
                                <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                    <i class="fas fa-check-circle text-primary text-sm mr-3"></i>
                                    <span class="text-xs text-gray-700">Validación de datos</span>
                                </div>
                            </div>

                            <a href="{{ $availableProcedures['inscripcion'] && !$availableProcedures['is_administrative'] ? route('tramites.inscripcion') : 'javascript:void(0)' }}"
                                class="w-full bg-gradient-to-r from-primary to-pink-600 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 text-sm shadow-lg inline-flex items-center justify-center {{ !$availableProcedures['inscripcion'] || $availableProcedures['is_administrative'] ? 'opacity-50 cursor-not-allowed' : 'hover:from-primary-dark hover:to-pink-700 transform hover:scale-105' }}">
                                <i class="fas fa-arrow-right mr-2"></i>
                                Comenzar Inscripción
                            </a>
                        </div>
                    </div>

                    <!-- Card 2: Renovación -->
                    <div
                        class="group bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-200 {{ !$availableProcedures['renovacion'] || $availableProcedures['is_administrative'] ? 'card-disabled' : '' }}">
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-sync-alt text-white text-xl"></i>
                            </div>
                            @if($availableProcedures['renovacion'] && isset($availableProcedures['estado_vigencia']['dias']))
                                <span class="bg-gradient-to-r from-amber-400 to-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                    {{ $availableProcedures['estado_vigencia']['dias'] }} DÍAS
                                </span>
                            @else
                                <span class="bg-gradient-to-r from-blue-400 to-blue-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                    ANUAL
                                </span>
                            @endif
                        </div>


                        <div>
                            <h3 class="text-xl font-bold mb-3 text-gray-800">Renovación</h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                @if($availableProcedures['renovacion'] && isset($availableProcedures['estado_vigencia']['dias']))
                                    Su registro vence en <span class="font-semibold text-amber-600">{{ $availableProcedures['estado_vigencia']['dias'] }} día(s)</span>. Es momento de renovar.
                                @else
                                    Renovación anual obligatoria para mantener activo su registro en el padrón de proveedores.
                                @endif
                            </p>

                            <div class="space-y-2 mb-6">
                                @if($availableProcedures['renovacion'] && isset($availableProcedures['estado_vigencia']['dias']))
                                    <div class="flex items-center p-2 rounded-lg feature-item border border-transparent bg-amber-50">
                                        <i class="fas fa-exclamation-triangle text-amber-500 text-sm mr-3"></i>
                                        <span class="text-xs text-amber-700 font-medium">¡Renovación urgente requerida!</span>
                                    </div>
                                    <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                        <i class="fas fa-clock text-blue-500 text-sm mr-3"></i>
                                        <span class="text-xs text-gray-700">{{ $availableProcedures['estado_vigencia']['dias'] }} día(s) restante(s)</span>
                                    </div>
                                @else
                                    <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                        <i class="fas fa-calendar-check text-blue-500 text-sm mr-3"></i>
                                        <span class="text-xs text-gray-700">Renovación anual requerida</span>
                                    </div>
                                @endif
                                <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                    <i class="fas fa-file-alt text-blue-500 text-sm mr-3"></i>
                                    <span class="text-xs text-gray-700">Actualización de documentos</span>
                                </div>
                                <div class="flex items-center p-2 rounded-lg feature-item border border-transparent">
                                    <i class="fas fa-shield-alt text-blue-500 text-sm mr-3"></i>
                                    <span class="text-xs text-gray-700">Mantener estatus activo</span>
                                </div>
                            </div>

                            <a href="{{ $availableProcedures['renovacion'] && !$availableProcedures['is_administrative'] ? route('tramites.renovacion') : 'javascript:void(0)' }}"
                                class="w-full bg-gradient-to-r from-blue-400 to-blue-500 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 text-sm shadow-lg inline-flex items-center justify-center {{ !$availableProcedures['renovacion'] || $availableProcedures['is_administrative'] ? 'opacity-50 cursor-not-allowed' : 'hover:from-blue-500 hover:to-blue-600 transform hover:scale-105' }}">
                                <i class="fas fa-redo mr-2"></i>
                                @if($availableProcedures['renovacion'] && isset($availableProcedures['estado_vigencia']['dias']))
                                    Renovar Ahora
                                @else
                                    Renovar Registro
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Card 3: Actualización -->
                    <div
                        class="group bg-white rounded-2xl p-6 shadow-lg card-hover border border-gray-200 {{ !$availableProcedures['actualizacion'] || $availableProcedures['is_administrative'] ? 'card-disabled' : '' }}">
                        <div class="flex items-center justify-between mb-6">
                            <div
                                class="w-14 h-14 bg-gradient-to-br from-stone-500 to-stone-600 rounded-xl flex items-center justify-center shadow-lg">
                                <i class="fas fa-edit text-white text-xl"></i>
                            </div>
                            @if($availableProcedures['actualizacion'] && isset($availableProcedures['estado_vigencia']['dias']))
                                <span class="bg-gradient-to-r from-green-500 to-green-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                    {{ $availableProcedures['estado_vigencia']['dias'] }} DÍAS
                                </span>
                            @else
                                <span class="bg-gradient-to-r from-stone-500 to-stone-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">
                                    EDITAR
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-xl font-bold mb-3 text-gray-800">Actualización</h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                @if($availableProcedures['actualizacion'] && isset($availableProcedures['estado_vigencia']['dias']))
                                    Su registro está activo con <span class="font-semibold text-green-600">{{ $availableProcedures['estado_vigencia']['dias'] }} día(s)</span> restantes. Puede actualizar su información.
                                @else
                                    Modifique información específica de su registro cuando sea necesario.
                                @endif
                            </p>
                            <div class="grid grid-cols-2 gap-2 mb-6">
                                <div
                                    class="bg-stone-100 rounded-lg p-3 text-center border border-stone-200 hover:border-stone-300 hover:bg-stone-200 transition-all duration-300 cursor-pointer group">
                                    <i
                                        class="fas fa-map-marker-alt text-stone-500 text-sm mb-1 group-hover:scale-110 transition-transform"></i>
                                    <div class="text-xs text-stone-700 font-medium">Dirección</div>
                                </div>
                                <div
                                    class="bg-stone-100 rounded-lg p-3 text-center border border-stone-200 hover:border-stone-300 hover:bg-stone-200 transition-all duration-300 cursor-pointer group">
                                    <i
                                        class="fas fa-phone text-stone-500 text-sm mb-1 group-hover:scale-110 transition-transform"></i>
                                    <div class="text-xs text-stone-700 font-medium">Contacto</div>
                                </div>
                                <div
                                    class="bg-stone-100 rounded-lg p-3 text-center border border-stone-200 hover:border-stone-300 hover:bg-stone-200 transition-all duration-300 cursor-pointer group">
                                    <i
                                        class="fas fa-briefcase text-stone-500 text-sm mb-1 group-hover:scale-110 transition-transform"></i>
                                    <div class="text-xs text-stone-700 font-medium">Actividades</div>
                                </div>
                                <div
                                    class="bg-stone-100 rounded-lg p-3 text-center border border-stone-200 hover:border-stone-300 hover:bg-stone-200 transition-all duration-300 cursor-pointer group">
                                    <i
                                        class="fas fa-users text-stone-500 text-sm mb-1 group-hover:scale-110 transition-transform"></i>
                                    <div class="text-xs text-stone-700 font-medium">Personal</div>
                                </div>
                            </div>

                            <a href="{{ $availableProcedures['actualizacion'] && !$availableProcedures['is_administrative'] ? route('tramites.actualizacion') : 'javascript:void(0)' }}"
                                class="w-full bg-gradient-to-r from-stone-500 to-stone-600 text-white font-bold py-3 px-4 rounded-xl transition-all duration-300 text-sm shadow-lg inline-flex items-center justify-center {{ !$availableProcedures['actualizacion'] || $availableProcedures['is_administrative'] ? 'opacity-50 cursor-not-allowed' : 'hover:from-stone-600 hover:to-stone-700 transform hover:scale-105' }}">
                                <i class="fas fa-cog mr-2"></i>
                                Actualizar Datos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        @push('scripts')
            <script>
                // Debug: Mostrar estado de variables
                console.log('Debug Trámites:', {
                    inscripcion: {{ $availableProcedures['inscripcion'] ? 'true' : 'false' }},
                    renovacion: {{ $availableProcedures['renovacion'] ? 'true' : 'false' }},
                    actualizacion: {{ $availableProcedures['actualizacion'] ? 'true' : 'false' }},
                    is_administrative: {{ $availableProcedures['is_administrative'] ? 'true' : 'false' }},
                    message: '{{ $availableProcedures['message'] }}'
                });

                document.addEventListener('DOMContentLoaded', function() {
                    const cards = document.querySelectorAll('.card-hover:not(.card-disabled)');

                    cards.forEach(card => {
                        card.addEventListener('mouseenter', function() {
                            this.style.transform = 'translateY(-4px) scale(1.02)';
                        });

                        card.addEventListener('mouseleave', function() {
                            this.style.transform = 'translateY(0) scale(1)';
                        });

                        card.addEventListener('touchstart', function() {
                            this.style.transform = 'translateY(-2px) scale(1.01)';
                        });

                        card.addEventListener('touchend', function() {
                            setTimeout(() => {
                                this.style.transform = 'translateY(0) scale(1)';
                            }, 150);
                        });
                    });

                });
            </script>
        @endpush
    @endsection
