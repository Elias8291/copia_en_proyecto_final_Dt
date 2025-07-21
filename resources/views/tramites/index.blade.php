@extends('layouts.app')

@section('title', 'Trámites Disponibles')

@section('content')
<div class="min-h-screen flex items-center justify-center py-8 px-4">
    <div class="w-full max-w-6xl bg-white rounded-3xl shadow-2xl border border-slate-200/50 overflow-hidden">
        <div class="text-center mb-8 p-6">
            <h1 class="text-3xl md:text-4xl font-bold text-[#9D2449] mb-3">Padrón de Proveedores</h1>
            <p class="text-base md:text-lg text-slate-600 max-w-xl mx-auto">Seleccione el trámite que desea realizar según su estado actual</p>
        </div>

     
        @if(isset($globalTramites['message']) && $globalTramites['message'])
        <div class="mt-">
            <div class="bg-slate-50 border border-slate-200 rounded-2xl shadow-sm p-6">
                <div class="flex items-start space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#9D2449] to-[#B91C1C] rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-800 mb-1">Estado de su registro</h3>
                        <p class="text-slate-600">{{ $globalTramites['message'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
        <div class="p-6 md:p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              
                <div class="group relative">
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $globalTramites['inscripcion'] ? 'border-blue-200 hover:border-blue-300 hover:shadow-xl' : 'border-slate-200' }} overflow-hidden transition-all duration-300 {{ $globalTramites['inscripcion'] ? 'hover:scale-105' : 'opacity-10' }}">
                        
                        @if(!$globalTramites['inscripcion'])
                            <div class="absolute inset-0 bg-slate-50/90 flex items-center justify-center z-10 rounded-2xl">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-slate-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-4.5V7a4 4 0 10-8 0v5.5"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 font-medium text-sm">No disponible</p>
                                </div>
                            </div>
                        @endif
                   
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $globalTramites['inscripcion'] ? 'from-blue-500 to-blue-600' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $globalTramites['inscripcion'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $globalTramites['inscripcion'] ? 'Disponible' : 'Bloqueado' }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Inscripción</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-4">Registro inicial en el padrón de proveedores para participar en licitaciones públicas.</p>
                            
                          
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-xs">
                                    <div class="w-4 h-4 {{ $globalTramites['inscripcion'] ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-2.5 h-2.5 {{ $globalTramites['inscripcion'] ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="{{ $globalTramites['inscripcion'] ? 'text-slate-700' : 'text-slate-500' }}">Documentación completa</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <div class="w-4 h-4 {{ $globalTramites['inscripcion'] ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-2.5 h-2.5 {{ $globalTramites['inscripcion'] ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="{{ $globalTramites['inscripcion'] ? 'text-slate-700' : 'text-slate-500' }}">Validación de actividades</span>
                                </div>
                            </div>
                        </div>

    
                        <div class="px-6 pb-6">
                            @if($globalTramites['inscripcion'])
                                <a href="{{ route('tramites.constancia', 'inscripcion') }}" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center group">
                                    <span>Iniciar</span>
                                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            @else
                                <button disabled class="w-full bg-slate-200 text-slate-500 font-semibold py-3 px-4 rounded-xl cursor-not-allowed">
                                    No Disponible
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="group relative">
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $globalTramites['renovacion'] ? 'border-amber-200 hover:border-amber-300 hover:shadow-xl' : 'border-slate-200' }} overflow-hidden transition-all duration-300 {{ $globalTramites['renovacion'] ? 'hover:scale-105' : 'opacity-60' }}">
                        
                        @if(!$globalTramites['renovacion'])
                            <div class="absolute inset-0 bg-slate-50/90 flex items-center justify-center z-10 rounded-2xl">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-slate-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-4.5V7a4 4 0 10-8 0v5.5"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 font-medium text-sm">No disponible</p>
                                </div>
                            </div>
                        @endif
                        
             
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $globalTramites['renovacion'] ? 'from-amber-500 to-orange-600' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $globalTramites['renovacion'] ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $globalTramites['renovacion'] ? 'Urgente' : 'Bloqueado' }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Renovación</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-4">Renueve su registro para mantener su habilitación y continuar participando.</p>
                            
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-xs">
                                    <div class="w-4 h-4 {{ $globalTramites['renovacion'] ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-2.5 h-2.5 {{ $globalTramites['renovacion'] ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="{{ $globalTramites['renovacion'] ? 'text-slate-700' : 'text-slate-500' }}">Actualización de datos</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <div class="w-4 h-4 {{ $globalTramites['renovacion'] ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-2.5 h-2.5 {{ $globalTramites['renovacion'] ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="{{ $globalTramites['renovacion'] ? 'text-slate-700' : 'text-slate-500' }}">Extensión de vigencia</span>
                                </div>
                            </div>
                        </div>

                                                 <div class="px-6 pb-6">
                             @if($globalTramites['renovacion'])
                                 <a href="{{ route('tramites.constancia', 'renovacion') }}" class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center group">
                                     <span>Renovar</span>
                                     <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                     </svg>
                                 </a>
                             @else
                                 <button disabled class="w-full bg-slate-200 text-slate-500 font-semibold py-3 px-4 rounded-xl cursor-not-allowed">
                                     No Disponible
                                 </button>
                             @endif
                         </div>
                    </div>
                </div>


                <div class="group relative">
                    <div class="bg-white rounded-2xl shadow-lg border-2 {{ $globalTramites['actualizacion'] ? 'border-emerald-200 hover:border-emerald-300 hover:shadow-xl' : 'border-slate-200' }} overflow-hidden transition-all duration-300 {{ $globalTramites['actualizacion'] ? 'hover:scale-105' : 'opacity-60' }}">
                        
                        @if(!$globalTramites['actualizacion'])
                            <div class="absolute inset-0 bg-slate-50/90 flex items-center justify-center z-10 rounded-2xl">
                                <div class="text-center">
                                    <div class="w-12 h-12 bg-slate-300 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m4-4.5V7a4 4 0 10-8 0v5.5"></path>
                                        </svg>
                                    </div>
                                    <p class="text-slate-600 font-medium text-sm">No disponible</p>
                                </div>
                            </div>
                        @endif
                        
                  
                        <div class="p-6 pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $globalTramites['actualizacion'] ? 'from-emerald-500 to-teal-600' : 'from-slate-300 to-slate-400' }} rounded-xl flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $globalTramites['actualizacion'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $globalTramites['actualizacion'] ? 'Disponible' : 'Bloqueado' }}
                                </span>
                            </div>
                            
                            <h3 class="text-lg md:text-xl font-bold text-slate-800 mb-2">Actualización</h3>
                            <p class="text-slate-600 text-sm leading-relaxed mb-4">Modifique la información de su empresa cuando sea necesario.</p>
                            
                            <!-- Warning if close to expiration -->
                            @if($globalTramites['actualizacion'] && isset($globalTramites['estado_vigencia']) && $globalTramites['estado_vigencia'] === 'por_vencer')
                                <div class="bg-amber-50 border-l-2 border-amber-400 p-2 mb-4 rounded text-xs">
                                    <p class="text-amber-700 font-medium">⚠️ Considere renovar en lugar de actualizar</p>
                                </div>
                            @endif
 
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-xs">
                                    <div class="w-4 h-4 {{ $globalTramites['actualizacion'] ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-2.5 h-2.5 {{ $globalTramites['actualizacion'] ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="{{ $globalTramites['actualizacion'] ? 'text-slate-700' : 'text-slate-500' }}">Modificar datos generales</span>
                                </div>
                                <div class="flex items-center text-xs">
                                    <div class="w-4 h-4 {{ $globalTramites['actualizacion'] ? 'bg-emerald-100' : 'bg-slate-100' }} rounded-full flex items-center justify-center mr-2">
                                        <svg class="w-2.5 h-2.5 {{ $globalTramites['actualizacion'] ? 'text-emerald-600' : 'text-slate-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="{{ $globalTramites['actualizacion'] ? 'text-slate-700' : 'text-slate-500' }}">Cambiar documentos</span>
                                </div>
                            </div>
                        </div>

                                                 <div class="px-6 pb-6">
                             @if($globalTramites['actualizacion'])
                                 <a href="{{ route('tramites.constancia', 'actualizacion') }}" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center group">
                                     <span>Actualizar</span>
                                     <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                     </svg>
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
</div>
@endsection