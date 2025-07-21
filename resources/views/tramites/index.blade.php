@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-6xl  bg-white mx-auto">
        
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200/50 p-6 mb-8">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h2M9 7h6m-6 4h6m-6 4h6M5 21v-5.172a2 2 0 01.586-1.414l2.828-2.828a2 2 0 012.828 0l2.828 2.828a2 2 0 01.586 1.414V21"></path></svg>
                </div>
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-slate-800">Padrón de Proveedores</h1>
                    <p class="text-base text-slate-600">Seleccione el trámite que desea realizar según su estado actual.</p>
                </div>
            </div>
        </div>

        <!-- Status Message -->
        @if(isset($globalTramites['message']) && $globalTramites['message'])
        <div class="mb-8">
            <div class="bg-blue-50 border-l-4 border-blue-400 rounded-r-lg p-4">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-1">Estado de su registro</h3>
                        <p class="text-blue-800">{{ $globalTramites['message'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Inscripción Card -->
            <div class="group flex flex-col">
                <div class="bg-white rounded-2xl shadow-lg border-2 {{ $globalTramites['inscripcion'] ? 'border-transparent hover:border-blue-500' : 'border-slate-200/80' }} overflow-hidden transition-all duration-300 h-full flex flex-col {{ $globalTramites['inscripcion'] ? 'group-hover:scale-105 group-hover:shadow-2xl' : 'opacity-80' }}">
                    <div class="p-6 flex-grow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br {{ $globalTramites['inscripcion'] ? 'from-blue-500 to-blue-600' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $globalTramites['inscripcion'] ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $globalTramites['inscripcion'] ? 'Disponible' : 'No Disponible' }}
                            </span>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Inscripción</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-4">Registro inicial en el padrón de proveedores para participar en licitaciones públicas.</p>
                        
                        @if(!$globalTramites['inscripcion'])
                        <div class="mt-4 bg-slate-50 border border-slate-200 rounded-lg p-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                </div>
                                <p class="text-xs text-slate-600 font-medium">Ya tiene una inscripción activa o en proceso.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="px-6 pb-6 mt-auto">
                        @if($globalTramites['inscripcion'])
                            <a href="{{ route('tramites.constancia', 'inscripcion') }}" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                <span>Iniciar</span>
                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        @else
                            <button disabled class="w-full bg-slate-200 text-slate-500 font-semibold py-3 px-4 rounded-xl cursor-not-allowed">
                                No Disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Renovación Card -->
            <div class="group flex flex-col">
                <div class="bg-white rounded-2xl shadow-lg border-2 {{ $globalTramites['renovacion'] ? 'border-transparent hover:border-amber-500' : 'border-slate-200/80' }} overflow-hidden transition-all duration-300 h-full flex flex-col {{ $globalTramites['renovacion'] ? 'group-hover:scale-105 group-hover:shadow-2xl' : 'opacity-80' }}">
                    <div class="p-6 flex-grow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br {{ $globalTramites['renovacion'] ? 'from-amber-500 to-orange-600' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $globalTramites['renovacion'] ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $globalTramites['renovacion'] ? 'Disponible' : 'No Disponible' }}
                            </span>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Renovación</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-4">Renueve su registro para mantener su habilitación y continuar participando.</p>
                        
                        @if(!$globalTramites['renovacion'])
                        <div class="mt-4 bg-slate-50 border border-slate-200 rounded-lg p-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                </div>
                                <p class="text-xs text-slate-600 font-medium">Disponible 7 días antes del vencimiento de su registro.</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="px-6 pb-6 mt-auto">
                         @if($globalTramites['renovacion'])
                             <a href="{{ route('tramites.constancia', 'renovacion') }}" class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                 <span>Renovar</span>
                                 <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                             </a>
                         @else
                             <button disabled class="w-full bg-slate-200 text-slate-500 font-semibold py-3 px-4 rounded-xl cursor-not-allowed">
                                 No Disponible
                             </button>
                         @endif
                     </div>
                </div>
            </div>

            <!-- Actualización Card -->
            <div class="group flex flex-col">
                <div class="bg-white rounded-2xl shadow-lg border-2 {{ $globalTramites['actualizacion'] ? 'border-transparent hover:border-emerald-500' : 'border-slate-200/80' }} overflow-hidden transition-all duration-300 h-full flex flex-col {{ $globalTramites['actualizacion'] ? 'group-hover:scale-105 group-hover:shadow-2xl' : 'opacity-80' }}">
                    <div class="p-6 flex-grow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 bg-gradient-to-br {{ $globalTramites['actualizacion'] ? 'from-emerald-500 to-teal-600' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $globalTramites['actualizacion'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ $globalTramites['actualizacion'] ? 'Disponible' : 'No Disponible' }}
                            </span>
                        </div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Actualización</h3>
                        <p class="text-slate-600 text-sm leading-relaxed mb-4">Modifique la información de su empresa cuando sea necesario.</p>
                        
                        @if(!$globalTramites['actualizacion'])
                        <div class="mt-4 bg-slate-50 border border-slate-200 rounded-lg p-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-slate-200 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path></svg>
                                </div>
                                <p class="text-xs text-slate-600 font-medium">Necesita un registro activo para actualizar datos.</p>
                            </div>
                        </div>
                        @endif

                        @if($globalTramites['actualizacion'] && isset($globalTramites['estado_vigencia']) && $globalTramites['estado_vigencia'] === 'por_vencer')
                            <div class="mt-4 bg-amber-50 border-l-2 border-amber-400 p-2 rounded text-xs">
                                <p class="text-amber-700 font-medium">⚠️ Considere renovar en lugar de actualizar.</p>
                            </div>
                        @endif
                    </div>
                     <div class="px-6 pb-6 mt-auto">
                         @if($globalTramites['actualizacion'])
                             <a href="{{ route('tramites.constancia', 'actualizacion') }}" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                 <span>Actualizar</span>
                                 <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                             </a>
                         @else
                             <button disabled class="w-full bg-slate-200 text-slate-500 font-semibold py-3 px-4 rounded-xl cursor-not-allowed">
                                 No Disponible
                             </button>
                         @endif
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection